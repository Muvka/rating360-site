import React, { forwardRef } from 'react';
import clsx from 'clsx';

import './styles.scss';
import { TextareaProps } from './types';

export const Textarea = forwardRef<HTMLTextAreaElement, TextareaProps>(({ className, ...props }, ref) => {
	return (
		<textarea ref={ref} {...props} className={clsx('textarea', className)} />
	);
});
