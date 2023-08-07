import React from 'react';

import crossIconId from '../../../images/shared/icons/icon-cross.svg';

const SelectClearIndicator = ({
	ref = null,
	innerProps = {},
	selectProps = {}
}) => {
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

export default SelectClearIndicator;
