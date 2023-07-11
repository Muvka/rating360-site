import React from 'react';
import RSelect, { components } from 'react-select';
import clsx from 'clsx';

import crossIconId from '../../../images/shared/icons/icon-cross.svg';
import chevronDownIconId from '../../../images/shared/icons/icon-chevron-down.svg';

const ClearIndicator = ({ ref = null, innerProps = {}, selectProps = {} }) => {
	return (
		<div
			{...innerProps}
			ref={ref}
			className={`${selectProps.classNamePrefix}__indicator-container`}
		>
			<svg
				width='16'
				height='16'
				className={`${selectProps.classNamePrefix}__icon`}
			>
				<use xlinkHref={`#${crossIconId}`} />
			</svg>
		</div>
	);
};

const DropdownIndicator = props => {
	return (
		<components.DropdownIndicator
			{...props}
			className={`${props.selectProps.classNamePrefix}__indicator-container`}
		>
			<svg
				width='16'
				height='16'
				className={`${props.selectProps.classNamePrefix}__icon`}
			>
				<use xlinkHref={`#${chevronDownIconId}`} />
			</svg>
		</components.DropdownIndicator>
	);
};

const MultiValueRemove = props => {
	return (
		<components.MultiValueRemove {...props}>
			<svg
				width='12'
				height='12'
				className={`${props.selectProps.classNamePrefix}__value-icon`}
			>
				<use xlinkHref={`#${crossIconId}`} />
			</svg>
		</components.MultiValueRemove>
	);
};

const Select = ({
	inputId = undefined,
	value = undefined,
	inputValue = undefined,
	isClearable = false,
	options = [],
	placeholder = 'Выберите',
	noOptionsMessage = 'Нет доступных опций',
	isMulti = false,
	className = '',
	onChange = () => {}
}) => {
	return (
		<RSelect
			inputId={inputId}
			value={value}
			options={options}
			placeholder={placeholder}
			isMulti={isMulti}
			inputValue={inputValue}
			isClearable={isClearable}
			unstyled
			noOptionsMessage={() => noOptionsMessage}
			className={clsx('select', className)}
			classNamePrefix='select'
			components={{
				ClearIndicator: ClearIndicator,
				DropdownIndicator: DropdownIndicator,
				MultiValueRemove: MultiValueRemove
			}}
			onChange={onChange}
		/>
	);
};

export default Select;
