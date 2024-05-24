import { createContext } from 'react';

import { ModalNotificationContextType } from './types';

const ModalNotificationContext = createContext<ModalNotificationContextType>({
    success: () => {},
    error: () => {},
    warning: () => {},
});

export default ModalNotificationContext;
