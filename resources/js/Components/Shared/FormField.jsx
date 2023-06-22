import React, { useId } from 'react';
import clsx from 'clsx';

const FormField = ({
	children = null,
	label = '',
	hiddenLabel = false,
	fieldset = false,
	error = '',
	className = ''
}) => {
	const inputId = useId();

	if (!children) {
		return null;
	}

	const ContainerComponent = fieldset ? 'fieldset' : 'div';
	const LabelComponent = fieldset ? 'legend' : 'label';

	const childrenWithProps = React.cloneElement(children, {
		id: Boolean(label) && !fieldset ? inputId : undefined,
		className: clsx(children.props?.className, 'form-field__input')
	});

	if (fieldset) {
		return (
			<fieldset className={clsx('form-field', className)}>
				{Boolean(label) && (
					<legend
						className={clsx('form-field__label', {
							'visually-hidden': hiddenLabel
						})}
					>
						{label}
					</legend>
				)}
				{childrenWithProps}
				{Boolean(error) && (
					<p className='form-field__error-message' aria-live='polite'>
						{error}
					</p>
				)}
			</fieldset>
		);
	}

	return (
		<ContainerComponent className={clsx('form-field', className)}>
			{Boolean(label) && (
				<LabelComponent
					htmlFor={fieldset ? undefined : inputId}
					className={clsx('text', 'form-field__label', {
						'visually-hidden': hiddenLabel
					})}
				>
					{label}
				</LabelComponent>
			)}
			{childrenWithProps}
			{Boolean(error) && (
				<p className='form-field__error-message' aria-live='polite'>
					{error}
				</p>
			)}
		</ContainerComponent>
	);
};

export default FormField;
