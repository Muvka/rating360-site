import React from 'react';
import { useForm } from '@inertiajs/react';
import clsx from 'clsx';
import toast from 'react-hot-toast';

import FormField from '../Shared/FormField.jsx';

const LoginForm = ({ className = '' }) => {
	const { data, setData, post, processing, errors } = useForm({
		email: '',
		password: ''
	});

	const handleSubmit = event => {
		event.preventDefault();

		post(route('client.user.login.login'), {
			onSuccess: () => {
				toast.success('Вы успешно авторизовались.');
			},
			onError: data => {
				toast.error('При отправке формы произошла ошибка!');
			}
		});
	};

	return (
		<div className={clsx('login-form', className)}>
			<h1 className='title title--center login-form__title'>Войти в аккаунт</h1>
			<form className='login-form__form' onSubmit={handleSubmit}>
				<FormField label='Введите свою почту' hiddenLabel error={errors.email}>
					<input
						type='email'
						name='email'
						value={data.email}
						placeholder='E-mail'
						className='text-input'
						onChange={event => setData('email', event.target.value)}
					/>
				</FormField>
				<FormField
					label='Введите свой пароль'
					hiddenLabel
					error={errors.password}
				>
					<input
						type='password'
						name='password'
						value={data.password}
						placeholder='Пароль'
						className='text-input'
						onChange={event => setData('password', event.target.value)}
					/>
				</FormField>
				<button
					type='submit'
					className='button button--accent button--medium'
					disabled={processing}
				>
					Войти
				</button>
			</form>
		</div>
	);
};

export default LoginForm;
