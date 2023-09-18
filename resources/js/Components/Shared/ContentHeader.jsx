import React from 'react';
import clsx from 'clsx';

const ContentHeader = ({
	title = '',
	content = null,
	trailing = null,
	className = ''
}) => {
	return (
		<div className={clsx('content-header', className)}>
			<div className='content-header__content-container'>
				<h1 className='title title--light content-header__title'>{title}</h1>
				{content}
			</div>
			{Boolean(trailing) && (
				<div className='content-header__trailing-container'>{trailing}</div>
			)}
		</div>
	);
};

export default ContentHeader;
