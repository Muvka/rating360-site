import { ComponentPropsWithoutRef } from 'react';

export interface IErrorMessageProps extends Omit<ComponentPropsWithoutRef<'p'>, 'children'> {
    text: string;
}
