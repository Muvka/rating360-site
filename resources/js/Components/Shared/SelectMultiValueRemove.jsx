import React from 'react';
import { components } from 'react-select';

import crossIconId from '../../../images/shared/icons/icon-cross.svg';

const SelectMultiValueRemove = props => {
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

export default SelectMultiValueRemove;
