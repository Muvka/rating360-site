import React, { useRef } from 'react';
import RAsyncSelect from 'react-select/async';
import clsx from 'clsx';

import SelectClearIndicator from './SelectClearIndicator.jsx';
import SelectDropdownIndicator from './SelectDropdownIndicator.jsx';
import SelectMultiValueRemove from './SelectMultiValueRemove.jsx';
import axios from 'axios';

const AsyncSelect = ({
	inputId = undefined,
	defaultValue = undefined,
	value = undefined,
	inputValue = undefined,
	isClearable = false,
	defaultOptions = [],
	placeholder = 'Выберите',
	loadingMessage = 'Загрузка',
	noOptionsMessage = 'Нет доступных опций',
	isMulti = false,
	className = '',
	loadDelay = 300,
	loadOptions: loadOptionsProp = inputValue => {},
	onChange = () => {}
}) => {
	const timerId = useRef(null);

	const loadOptions = (inputValue, callback) => {
		if (timerId.current !== null) {
			clearTimeout(timerId.current);
			timerId.current = null;
		}

		timerId.current = setTimeout(async () => {
			const data = await loadOptionsProp(inputValue);
			callback(data);
		}, loadDelay);
	};

	return (
		<RAsyncSelect
			inputId={inputId}
			defaultValue={defaultValue}
			value={value}
			defaultOptions={defaultOptions}
			placeholder={placeholder}
			isMulti={isMulti}
			inputValue={inputValue}
			isClearable={isClearable}
			unstyled
			cacheOptions
			loadingMessage={() => loadingMessage}
			noOptionsMessage={() => noOptionsMessage}
			className={clsx('select', className)}
			classNamePrefix='select'
			components={{
				ClearIndicator: SelectClearIndicator,
				DropdownIndicator: SelectDropdownIndicator,
				MultiValueRemove: SelectMultiValueRemove
			}}
			loadOptions={loadOptions}
			onChange={onChange}
		/>
	);
};

export default AsyncSelect;
