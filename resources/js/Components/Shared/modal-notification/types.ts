import { ReactNode, MouseEvent } from 'react';

export enum ModalNotificationTypes {
    SUCCESS = 'success',
    ERROR = 'error',
    WARNING = 'warning',
}

export type ModalNotificationContextType = {
    success: (message: string, buttons?: IModalNotificationButton[]) => void;
    error: (message: string, buttons?: IModalNotificationButton[]) => void;
    warning: (message: string, buttons?: IModalNotificationButton[]) => void;
};

export type ModalNotificationProviderProps = {
    children: ReactNode;
};

export interface IModalNotificationButton {
    text: string;
    variant?: 'primary' | 'accent';
    onClick?: () => void;
}

export type ModalNotificationProps = {
    type: ModalNotificationTypes;
    message: string;
    buttons?: IModalNotificationButton[],
    onBackdropClick?: (event: MouseEvent) => void;
    onCloseButtonClick?: () => void;
};
