import React, { useId } from 'react';
import clsx from 'clsx';

const RadioButton = ({
	name = '',
	label = '',
	value = '',
	checked = false,
	className = '',
	onChange = () => {}
}) => {
	const inputId = useId();

	return (
		<div className={clsx('radio-button', className)}>
			<input
				type='radio'
				name={name}
				value={value}
				id={inputId}
				checked={checked}
				className='radio-button__input'
				onChange={event => onChange(event, value)}
			/>
			<label htmlFor={inputId} className='radio-button__label'>
				{label}
			</label>
		</div>
	);
};

export default RadioButton;
