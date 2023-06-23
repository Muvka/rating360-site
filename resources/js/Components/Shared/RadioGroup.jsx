import React from 'react';
import clsx from 'clsx';
import RadioButton from './RadioButton.jsx';

const RadioGroup = ({
	name = '',
	options = [],
	value = '',
	className = '',
	onChange = () => {}
}) => {
	return (
		<div className={clsx('radio-group', className)}>
			{options.map(option => (
				<RadioButton
					key={option.value}
					name={name}
					label={option.label}
					value={option.value}
					checked={option.value === value}
					onChange={onChange}
				/>
			))}
		</div>
	);
};

export default RadioGroup;
