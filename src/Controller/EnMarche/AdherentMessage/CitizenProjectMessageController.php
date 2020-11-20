<?php

namespace App\Controller\EnMarche\AdherentMessage;

use App\AdherentMessage\AdherentMessageDataObject;
use App\AdherentMessage\AdherentMessageFactory;
use App\AdherentMessage\AdherentMessageManager;
use App\AdherentMessage\AdherentMessageStatusEnum;
use App\AdherentMessage\AdherentMessageTypeEnum;
use App\Entity\Adherent;
use App\Entity\AdherentMessage\AbstractAdherentMessage;
use App\Entity\AdherentMessage\CitizenProjectAdherentMessage;
use App\Entity\AdherentMessage\Filter\CitizenProjectFilter;
use App\Entity\CitizenProject;
use App\Form\AdherentMessage\AdherentMessageType;
use App\Mailchimp\Manager;
use App\Repository\AdherentMessageRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route(path="/espace-porteur-projet/{citizen_project_slug}/messagerie", name="app_message_citizen_project_")
 *
 * @ParamConverter("citizenProject", options={"mapping": {"citizen_project_slug": "slug"}})
 *
 * @Security("is_granted('ADMINISTRATE_CITIZEN_PROJECT', citizenProject) and citizenProject.isApproved()")
 */
class CitizenProjectMessageController extends Controller
{
    /**
     * @Route(name="list", methods={"GET"})
     *
     * @param Adherent|UserInterface $adherent
     */
    public function messageListAction(
        Request $request,
        UserInterface $adherent,
        AdherentMessageRepository $repository,
        CitizenProject $citizenProject
    ): Response {
        $status = $request->query->get('status');

        if ($status && !AdherentMessageStatusEnum::isValid($status)) {
            throw new BadRequestHttpException('Invalid status');
        }

        return $this->renderTemplate('message/list.html.twig', $citizenProject, [
            'messages' => $paginator = $repository->findAllCitizenProjectMessage(
                $adherent,
                $citizenProject,
                $status,
                $request->query->getInt('page', 1)
            ),
            'total_message_count' => $status ?
                $repository->countTotalCitizenProjectMessage($adherent, $citizenProject) :
                $paginator->getTotalItems(),
            'message_filter_status' => $status,
        ]);
    }

    /**
     * @Route("/creer", name="create", methods={"GET", "POST"})
     *
     * @param Adherent|UserInterface $adherent
     */
    public function createMessageAction(
        Request $request,
        UserInterface $adherent,
        CitizenProject $citizenProject,
        AdherentMessageManager $manager
    ): Response {
        $form = $this
            ->createForm(AdherentMessageType::class)
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $message = AdherentMessageFactory::create($adherent, $form->getData(), AdherentMessageTypeEnum::CITIZEN_PROJECT);
            $message->setFilter(new CitizenProjectFilter($citizenProject));

            $manager->saveMessage($message);

            $this->addFlash('info', 'adherent_message.created_successfully');

            if ($form->get('next')->isClicked()) {
                return $this->redirectToRoute('app_message_citizen_project_filter', [
                    'uuid' => $message->getUuid()->toString(),
                    'citizen_project_slug' => $citizenProject->getSlug(),
                ]);
            }

            return $this->redirectToRoute('app_message_citizen_project_update', [
                'uuid' => $message->getUuid(),
                'citizen_project_slug' => $citizenProject->getSlug(),
            ]);
        }

        return $this->renderTemplate('message/create.html.twig', $citizenProject, ['form' => $form->createView()]);
    }

    /**
     * @Route("/{uuid}/modifier", requirements={"uuid": "%pattern_uuid%"}, name="update", methods={"GET", "POST"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message)")
     */
    public function updateMessageAction(
        Request $request,
        CitizenProjectAdherentMessage $message,
        AdherentMessageManager $manager,
        CitizenProject $citizenProject
    ): Response {
        if ($message->isSent()) {
            throw new BadRequestHttpException('This message has already been sent.');
        }

        $form = $this
            ->createForm(AdherentMessageType::class, $dataObject = AdherentMessageDataObject::createFromEntity($message))
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->updateMessage($message, $dataObject);

            $this->addFlash('info', 'adherent_message.updated_successfully');

            if ($form->get('next')->isClicked()) {
                return $this->redirectToRoute('app_message_citizen_project_filter', [
                    'uuid' => $message->getUuid()->toString(),
                    'citizen_project_slug' => $citizenProject->getSlug(),
                ]);
            }

            return $this->redirectToRoute('app_message_citizen_project_update', [
                'uuid' => $message->getUuid(),
                'citizen_project_slug' => $citizenProject->getSlug(),
            ]);
        }

        return $this->renderTemplate('message/update.html.twig', $citizenProject, ['form' => $form->createView()]);
    }

    /**
     * @Route("/{uuid}/filtrer", name="filter", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message)")
     */
    public function filterMessageAction(
        CitizenProjectAdherentMessage $message,
        CitizenProject $citizenProject
    ): Response {
        if ($message->isSent()) {
            throw new BadRequestHttpException('This message has been already sent.');
        }

        return $this->renderTemplate('message/filter/citizen_project.html.twig', $citizenProject, ['message' => $message]);
    }

    /**
     * @Route("/{uuid}/visualiser", name="preview", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message)")
     */
    public function previewMessageAction(
        CitizenProjectAdherentMessage $message,
        CitizenProject $citizenProject
    ): Response {
        if (!$message->isSynchronized()) {
            throw new BadRequestHttpException('Message preview is not ready yet.');
        }

        return $this->renderTemplate('message/preview.html.twig', $citizenProject, ['message' => $message]);
    }

    /**
     * @Route("/{uuid}/supprimer", name="delete", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message)")
     */
    public function deleteMessageAction(
        CitizenProjectAdherentMessage $message,
        ObjectManager $manager,
        CitizenProject $citizenProject
    ): Response {
        $manager->remove($message);
        $manager->flush();

        $this->addFlash('info', 'adherent_message.deleted_successfully');

        return $this->redirectToRoute('app_message_citizen_project_list', ['citizen_project_slug' => $citizenProject->getSlug()]);
    }

    /**
     * @Route("/{uuid}/send", name="send", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message)")
     */
    public function sendMessageAction(
        CitizenProjectAdherentMessage $message,
        Manager $manager,
        ObjectManager $entityManager,
        CitizenProject $citizenProject
    ): Response {
        if (!$message->isSynchronized()) {
            throw new BadRequestHttpException('The message is not ready to send yet.');
        }

        if (!$message->getRecipientCount()) {
            throw new BadRequestHttpException('Your message should have a filter');
        }

        if ($message->isSent()) {
            throw new BadRequestHttpException('This message has already been sent.');
        }

        if ($manager->sendCampaign($message)) {
            $message->markAsSent();
            $entityManager->flush();

            $this->addFlash('info', 'adherent_message.campaign_sent_successfully');
        } else {
            $this->addFlash('error', 'adherent_message.campaign_sent_failure');
        }

        return $this->redirectToRoute('app_message_citizen_project_list', ['citizen_project_slug' => $citizenProject->getSlug()]);
    }

    /**
     * @Route("/{uuid}/confirmation", name="send_success", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message) and message.isSent()")
     */
    public function sendSuccessAction(AbstractAdherentMessage $message, CitizenProject $citizenProject): Response
    {
        return $this->renderTemplate('message/send_success/default.html.twig', $citizenProject, ['message' => $message]);
    }

    /**
     * @Route("/{uuid}/tester", name="test", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHOR_OF', message)")
     */
    public function sendTestMessageAction(
        CitizenProjectAdherentMessage $message,
        Manager $manager,
        CitizenProject $citizenProject
    ): Response {
        if (!$message->isSynchronized()) {
            throw new BadRequestHttpException('The message is not yet ready to test sending.');
        }

        if ($manager->sendTestCampaign($message, [$this->getUser()->getEmailAddress()])) {
            $this->addFlash('info', 'adherent_message.test_campaign_sent_successfully');
        } else {
            $this->addFlash('info', 'adherent_message.test_campaign_sent_failure');
        }

        return $this->redirectToRoute('app_message_citizen_project_filter', [
            'citizen_project_slug' => $citizenProject->getSlug(),
            'uuid' => $message->getUuid()->toString(),
        ]);
    }

    private function renderTemplate(string $template, CitizenProject $citizenProject, array $parameters)
    {
        return $this->render($template, array_merge(
            $parameters,
            [
                'base_template' => 'message/_base_citizen_project.html.twig',
                'message_type' => AdherentMessageTypeEnum::CITIZEN_PROJECT,
                'citizen_project' => $citizenProject,
                'route_params' => ['citizen_project_slug' => $citizenProject->getSlug()],
            ]
        ));
    }
}
