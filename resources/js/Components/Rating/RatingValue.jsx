import React, { useId } from 'react';
import clsx from 'clsx';
import { Tooltip } from 'react-tooltip';

const sizes = ['default', 'small'];

const RatingValue = ({
	value = 0,
	extraValue = 0,
	size = 'default',
	className = ''
}) => {
	const checkedSize = sizes.includes(size) ? size : sizes[0];
	const ratingValueId = useId();

	return (
		<>
			<span
				id={ratingValueId}
				className={clsx(
					'rating-value',
					{
						'rating-value--small': checkedSize === 'small'
					},
					className
				)}
			>
				{value.toFixed(1)}
				{Boolean(extraValue) && `/${extraValue.toFixed(1)}`}
			</span>
			<Tooltip
				anchorSelect={`#${ratingValueId.replace(/:/g, '\\:')}`}
				content='С самооценкой/Без самооценки'
				place='top'
			/>
		</>
	);
};

export default RatingValue;
