import React from 'react';
import { render } from 'react-dom';
import Modal from '../components/Modal';

export default function () {
    findAll(document, '.em-modal--trigger').forEach((element) => {
        on(element, 'click', (event) => {
            event.preventDefault();

            const modalWrapper = document.createElement('div');
            element.parentNode.insertBefore(modalWrapper, element);

            render(
                <Modal
                    side={element.dataset.modalSide || null}
                    content={dom(element.dataset.contentElement).innerHTML}
                    closeCallback={() => { modalWrapper.remove(); }}
                />,
                modalWrapper
            );
        });
    });
}
