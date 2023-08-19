import React, { useId, useState } from 'react';
import clsx from 'clsx';

import useOutsideClick from '../../Hooks/useOutsideClick.jsx';
import burgerIconId from '../../../images/shared/icons/icon-burger.svg';
import crossIconId from '../../../images/shared/icons/icon-cross.svg';

const MainMenu = ({ children = null, className = '' }) => {
	const [isOpen, setIsOpen] = useState(false);
	const contentContainerId = useId();
	const buttonLabel = isOpen ? 'Закрыть меню' : 'Открыть меню';
	const outsideClickRef = useOutsideClick(() => setIsOpen(false));

	if (!children) return false;

	return (
		<div ref={outsideClickRef} className={clsx('main-menu', className)}>
			<button
				type='button'
				className='main-menu__button'
				aria-label={buttonLabel}
				aria-expanded={isOpen}
				aria-controls={contentContainerId}
				onClick={() => setIsOpen(!isOpen)}
			>
				<svg
					width='24'
					height='24'
					className='main-menu__icon'
					aria-hidden='true'
				>
					<use xlinkHref={`#${isOpen ? crossIconId : burgerIconId}`} />
				</svg>
			</button>
			<div
				id={contentContainerId}
				className={clsx('main-menu__content-container', {
					'main-menu__content-container--hidden': !isOpen
				})}
			>
				{children}
			</div>
		</div>
	);
};

export default MainMenu;
