import React, { Children } from 'react';

const SeparateWrapper = ({ children = null }) => {
	const arrayChildren = Children.toArray(children);

	return (
		<>
			{Children.map(arrayChildren, (childrenComponent, index) => {
				return (
					Boolean(childrenComponent) && (
						<>
							{index > 0 && <div className='divider' />}
							{childrenComponent}
						</>
					)
				);
			})}
		</>
	);
};

export default SeparateWrapper;
