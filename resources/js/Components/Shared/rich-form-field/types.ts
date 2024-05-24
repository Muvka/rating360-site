import { ReactElement } from 'react';

export enum RichFormFieldVariant {
    STANDARD = 'standard',
    GROUPED = 'grouped'
}

export interface IRichFormFieldProps {
    children: ReactElement;
    label: string;
    description?: string;
    required?: boolean;
    fieldIdProp?: string;
    variant?: RichFormFieldVariant;
    error?: string;
    className?: string;
}
