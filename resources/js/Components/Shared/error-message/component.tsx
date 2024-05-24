import React from 'react';
import clsx from 'clsx';

import './styles.scss';
import { IErrorMessageProps } from './types';

const ErrorMessage = ({ text, className, ...props }: IErrorMessageProps) => {
	return (
		<p {...props} className={clsx('error-message', className)}>{text}</p>
	);
};

export default ErrorMessage;
