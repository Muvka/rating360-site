import React from 'react';
import clsx from 'clsx';

import checkIconId from '../../../images/shared/icons/icon-check.svg';

const Checkbox = ({
	label = '',
	checked = false,
	className = '',
	onChange = () => {}
}) => {
	return (
		<label className={clsx('checkbox', className)}>
			<input
				type='checkbox'
				checked={checked}
				className='checkbox__input'
				onChange={onChange}
			/>
			<span className='checkbox__indicator'>
				<svg
					width='14'
					height='14'
					className='checkbox__icon'
					aria-hidden='true'
				>
					<use xlinkHref={`#${checkIconId}`} />
				</svg>
			</span>
			{label}
		</label>
	);
};

export default Checkbox;
