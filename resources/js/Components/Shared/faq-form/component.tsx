import React, { useEffect, useId } from 'react';
import { router, usePage } from '@inertiajs/react';
import { useForm } from 'react-hook-form';
import clsx from 'clsx';

import './styles.scss';
import { IFaqFormProps } from './types.ts';
import { IFaqForm } from '@js/types/shared/faq';
import { useModalNotification } from '@js/Components/Shared/modal-notification';
import { RichFormField } from '@js/Components/Shared/rich-form-field';
import { Textarea } from '@js/Components/Shared/fields/textarea';
import { Button } from '@js/Components/Shared/buttons/button';

export const FaqForm = ({ className }: IFaqFormProps) => {
    const { errors: backendErrors } = usePage().props;
    const { success } = useModalNotification();
    const {
        register,
        handleSubmit: useFormHandleSubmit,
        setError,
        reset,
        formState: { isSubmitting, errors }
    } = useForm<IFaqForm>();
    const titleId = useId();
    const formRef = React.useRef<HTMLFormElement>(null);

    const handleSubmit = (data: IFaqForm) => {
        if (formRef.current) {
            router.post(formRef.current.action, data as Record<string, any>, {
                only: ['errors'],
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    success('Ваш вопрос был успешно отправлен.');
                    reset();
                }
            });
        }
    };

    useEffect(() => {
        Object.entries(backendErrors).forEach(([key, message]) => {
            setError(key as keyof IFaqForm, {
                type: 'validation',
                message: message
            });
        });
    }, [backendErrors]);

    return (
        <section className={clsx('faq-form', className)} aria-labelledby={titleId}>
            <h2 id={titleId} className='title title--small faq-form__title'>Остались вопросы?</h2>
            <form ref={formRef}
                  action={route('client.shared.faqs.send')}
                  className='faq-form__form'
                  onSubmit={useFormHandleSubmit(handleSubmit)}>
                <RichFormField label='Вопрос'
                               description='Напишите свой вопрос, чтобы мы могли вам помочь'
                               error={errors.question?.message}>
                    <Textarea placeholder='Что пошло не так?'
                              rows={5}
                              {...register('question')}
                    />
                </RichFormField>
                <Button type='submit' disabled={isSubmitting}>Отправить</Button>
            </form>
        </section>
    );
};
