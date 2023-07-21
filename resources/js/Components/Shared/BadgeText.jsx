import React from 'react';
import clsx from 'clsx';

const variants = ['default', 'accent'];

const BadgeText = ({ text = '', variant = 'default', className = '' }) => {
	const checkedVariant = variants.includes(variant) ? variant : variants[0];

	if (!text) return false;

	return (
		<span
			className={clsx(
				'badge-text',
				'text',
				{
					'badge-text--accent': checkedVariant === 'accent',
					'text--light': checkedVariant === 'default',
					'text--accent': checkedVariant === 'accent'
				},
				className
			)}
		>
			{text}
		</span>
	);
};

export default BadgeText;
