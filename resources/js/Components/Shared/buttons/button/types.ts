import { FC } from 'react';
import { InertiaLinkProps } from '@inertiajs/react';

export enum ButtonVariant {
    Primary,
    Secondary,
}

export enum ButtonSize {
    Tiny,
    Small,
    Medium,
}

export type ButtonProps = Omit<InertiaLinkProps, 'size' | 'href'> & {
	component?: FC<InertiaLinkProps>;
	href?: string;
	leadingIcon?: string;
	trailingIcon?: string;
	variant?: ButtonVariant;
	size?: ButtonSize;
};
