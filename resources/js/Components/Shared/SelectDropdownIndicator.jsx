import React from 'react';
import { components } from 'react-select';

import chevronDownIconId from '../../../images/shared/icons/icon-chevron-down.svg';

const SelectDropdownIndicator = props => {
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

export default SelectDropdownIndicator;
