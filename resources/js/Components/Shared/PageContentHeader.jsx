import React from 'react';
import clsx from 'clsx';

const PageContentHeader = ({
	title = '',
	content = null,
	trailing = null,
	className = ''
}) => {
	return (
		<div className={clsx('page-content-header', className)}>
			<div className='page-content-header__content-container'>
				<h1 className='title title--light page-content-header__title'>
					{title}
				</h1>
				{content}
			</div>
			{Boolean(trailing) && (
				<div className='page-content-header__trailing-container'>
					{trailing}
				</div>
			)}
		</div>
	);
};

export default PageContentHeader;
