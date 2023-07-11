import React from 'react';
import { Head, Link } from '@inertiajs/react';

import BlankLayout from '../../Layouts/BlankLayout.jsx';

const ErrorPage = ({ status = 0 }) => {
	const title = `Ошибка ${status}`;
	const description = {
		403: 'Извините, у вас нет разрешения на доступ к запрашиваемому ресурсу.',
		404: 'К сожалению, запрашиваемая страница не найдена. Пожалуйста, проверьте правильность введенного URL или вернитесь на главную страницу.',
		500: 'Произошла внутренняя ошибка сервера. Пожалуйста, попробуйте обновить страницу или вернитесь позже. Если проблема сохраняется, пожалуйста, свяжитесь с администратором сайта для получения дополнительной помощи.',
		503: 'Сервис временно недоступен. Пожалуйста, попробуйте обновить страницу или вернитесь позже. Извините за временные неудобства, мы работаем над восстановлением сервиса.'
	}[status];

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<div className='error-notification container'>
				<h1 className='title title--big title--center error-notification__title'>
					{title}
				</h1>
				{Boolean(description) && (
					<p className='text text--center error-notification__description'>
						{description}
					</p>
				)}
				<Link
					href={route('client.rating.ratings.index')}
					className='button button--accent error-notification__button'
				>
					Вернуться на главную
				</Link>
			</div>
		</>
	);
};

ErrorPage.layout = page => <BlankLayout children={page} />;

export default ErrorPage;
