import React, { forwardRef } from 'react';

import './styles.scss';
import { ModalNotificationProps, ModalNotificationTypes } from './types.ts';
import { Button, ButtonSize, ButtonVariant } from '@js/Components/Shared/buttons/button';
import crossIconId from '@icons/icon-cross.svg';

export const ModalNotification = forwardRef<HTMLDialogElement, ModalNotificationProps>((
    { type, buttons, message, onBackdropClick, onCloseButtonClick }: ModalNotificationProps,
    forwardedRef
) => {
    let title = '';
    const paragraphs = message.split('\n');

    if (type === ModalNotificationTypes.SUCCESS) {
        title = 'Успех!';
    } else if (type === ModalNotificationTypes.ERROR) {
        title = 'Ошибка!';
    } else if (type === ModalNotificationTypes.WARNING) {
        title = 'Внимание!';
    }

    return (
        <dialog ref={forwardedRef} className='dialog dialog--notification' onClick={onBackdropClick}>
            <div className={`notification notification--${type}`}>
                <h2 className='title title--center notification__title'>{title}</h2>
                <div className='notification__message-container'>
                    {paragraphs.map((paragraph, index) => (
                        <p
                            key={index}
                            className='text text--center notification__message'
                        >
                            {paragraph}
                        </p>
                    ))}
                </div>
                {buttons !== undefined && buttons.length && (
                    <div className='notification__buttons-container'>
                        {buttons.map((button, index) => (
                            <Button key={index}
                                    type='button'
                                    variant={button.variant === 'accent' ? ButtonVariant.Primary : ButtonVariant.Secondary}
                                    size={ButtonSize.Small}
                                    className='notification__button'
                                    onClick={() => {
                                        onCloseButtonClick?.();
                                        button.onClick?.();
                                    }}>
                                {button.text}
                            </Button>
                        ))}
                    </div>
                )}
            </div>
            <button type='button' className='dialog__close-button' aria-label='Закрыть диалог'
                    onClick={onCloseButtonClick}>
                <svg
                    width='16'
                    height='16'
                    className='dialog__close-icon'
                >
                    <use xlinkHref={`#${crossIconId}`} />
                </svg>
            </button>
        </dialog>
    );
});
