import React from 'react';
import RSelect from 'react-select';
import clsx from 'clsx';

import SelectClearIndicator from './SelectClearIndicator.jsx';
import SelectDropdownIndicator from './SelectDropdownIndicator.jsx';
import SelectMultiValueRemove from './SelectMultiValueRemove.jsx';

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
				ClearIndicator: SelectClearIndicator,
				DropdownIndicator: SelectDropdownIndicator,
				MultiValueRemove: SelectMultiValueRemove
			}}
			onChange={onChange}
		/>
	);
};

export default Select;
