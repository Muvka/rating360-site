import React, { useCallback, useId } from 'react';
import clsx from 'clsx';

import './styles.scss';
import { IRichFormFieldProps, RichFormFieldVariant } from './types';
import { ErrorMessage } from '@js/Components/Shared/error-message';

export const RichFormField = (
	{
		children,
		label,
		description,
		required = false,
		fieldIdProp = 'id',
		variant = RichFormFieldVariant.STANDARD,
		error,
		className
	}: IRichFormFieldProps
) => {
	const inputId = useId();
	const labelId = useId();
	const errorMessageId = useId();

	const childrenWithProps = React.cloneElement(children, {
		[fieldIdProp]: variant !== 'grouped' ? inputId : undefined,
		'aria-invalid': Boolean(error),
		'aria-errormessage': errorMessageId
	});

	const renderLabel = useCallback(() => {
		const className = clsx('title title--tiny rich-form-field__label', { 'rich-form-field__label--required': required });

		return variant === 'standard' ? (
			<label htmlFor={inputId} className={className}>{label}</label>
		) : (
			<p id={labelId} className={className}>{label}</p>
		);
	}, [label, required, variant, inputId, labelId]);

	const renderDescription = useCallback(() => {
		if (!description) {
			return null;
		}

		return <p className='rich-form-field__description'>{description}</p>;
	}, [description]);

	const renderError = useCallback(() => {
		if (!error) {
			return null;
		}

		return <ErrorMessage id={errorMessageId} text={error} className='rich-form-field__error-message' />;
	}, [error, errorMessageId]);

	return (
		<div role={variant === 'grouped' ? 'group' : undefined} className={clsx('rich-form-field', className)}
			 aria-labelledby={variant === 'grouped' ? labelId : undefined}>
			<div className='rich-form-field__group'>
				{renderLabel()}
				{renderDescription()}
			</div>
			<div className='rich-form-field__group'>
				{childrenWithProps}
				{renderError()}
			</div>
		</div>
	);
};
