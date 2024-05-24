import React, { FC } from 'react';
import clsx from 'clsx';

import './styles.scss';
import { ButtonProps, ButtonSize, ButtonVariant } from './types';

export const Button = ({
	children,
	component,
	trailingIcon,
	leadingIcon,
	variant = ButtonVariant.Primary,
	size = ButtonSize.Medium,
	className,
	...restProps
}: ButtonProps) => {
	let Component: string | FC<any>;

	if (restProps.href) {
		Component = 'a';
	} else {
		Component = 'button';
	}

	if (component) {
		Component = component;
	}

	return (
		<Component
			{...restProps}
			className={clsx(
				'button',
				{
					'button--tiny': size === ButtonSize.Tiny,
					'button--small': size === ButtonSize.Small,
					'button--medium': size === ButtonSize.Medium,
					'button--primary': variant === ButtonVariant.Primary,
					'button--secondary': variant === ButtonVariant.Secondary
				},
				className
			)}
		>
			{leadingIcon !== undefined && (
				<svg width='24' height='24' className='button__icon' aria-hidden='true'>
					<use xlinkHref={`#${leadingIcon}`} />
				</svg>
			)}
			{children}
			{trailingIcon !== undefined && (
				<svg width='24' height='24' className='button__icon' aria-hidden='true'>
					<use xlinkHref={`#${trailingIcon}`} />
				</svg>
			)}
		</Component>
	);
};
