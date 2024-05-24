import React, { useId } from 'react';
import clsx from 'clsx';

import './styles.scss';
import { FaqListProps } from './types';
import { FaqItem } from './faq-item';

const FaqList = ({ items, className }: FaqListProps) => {
    const titleId = useId();

	return (
        <section className={clsx('faq-list', className)} aria-labelledby={titleId}>
            <h2 id={titleId} className='visually-hidden'>Список ответов на вопросы</h2>
			{items.length ? (
				<ul className='faq-list__list'>
					{items.map(faq => (
						<li key={faq.id} className='faq-list__item'>
							<FaqItem question={faq.question} answer={faq.answer} />
						</li>
					))}
				</ul>
			) : (
				<p className='text faq-list__text'>На данный момент вопросы отсутствуют.</p>
			)}
		</section>
	);
};

export default FaqList;
