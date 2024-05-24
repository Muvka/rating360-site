import { useContext } from 'react';

import ModalNotificationContext from './context';

export const useModalNotification = () => {
	return useContext(ModalNotificationContext);
};
