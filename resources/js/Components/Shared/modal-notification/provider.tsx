import React, { MouseEvent, useRef, useState } from 'react';

import { IModalNotificationButton, ModalNotificationProviderProps, ModalNotificationTypes } from './types.ts';
import ModalNotificationContext from './context';
import { ModalNotification } from './component.tsx';

export const ModalNotificationProvider = ({ children }: ModalNotificationProviderProps) => {
    const [type, setType] = useState<ModalNotificationTypes>(ModalNotificationTypes.SUCCESS);
    const [message, setMessage] = useState<string>('');
    const [buttons, setButtons] = useState<IModalNotificationButton[] | undefined>(undefined);
    const dialogRef = useRef<HTMLDialogElement>(null);

    const error = (message: string, buttons?: IModalNotificationButton[]) => {
        setType(ModalNotificationTypes.ERROR);
        setMessage(message);
        setButtons(buttons);
        showDialog();
    };

    const warning = (message: string, buttons?: IModalNotificationButton[]) => {
        setType(ModalNotificationTypes.WARNING);
        setMessage(message);
        setButtons(buttons);
        showDialog();
    };

    const success = (message: string, buttons?: IModalNotificationButton[]) => {
        setType(ModalNotificationTypes.SUCCESS);
        setMessage(message);
        setButtons(buttons);
        showDialog();
    };

    const showDialog = () => {
        dialogRef.current?.showModal();
    };

    const hideDialog = () => {
        dialogRef.current?.close();
    };

    const handleBackdropClick = (event: MouseEvent) => {
        if (event.target === dialogRef.current) {
            hideDialog();
        }
    };

    return (
        <ModalNotificationContext.Provider value={{ success, error, warning }}>
            {children}
            <ModalNotification ref={dialogRef}
                               type={type}
                               message={message}
                               buttons={buttons}
                               onBackdropClick={handleBackdropClick}
                               onCloseButtonClick={hideDialog} />
        </ModalNotificationContext.Provider>
    );
};
