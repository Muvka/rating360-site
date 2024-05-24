import React from 'react';
import clsx from 'clsx';

import './styles.scss';
import { FaqItemProps } from './types';
import chevronDownIcon from '@icons/icon-chevron-down.svg';

const FaqItem = ({ question, answer, className }: FaqItemProps) => {
	return (
		<details className={clsx('faq-item', className)}>
			<summary className='faq-item__question'>
				{question}
				<span className='faq-item__icon-container'>
					<svg className='faq-item__icon' width='16' height='16' aria-hidden='true'>
						<use xlinkHref={`#${chevronDownIcon}`} />
					</svg>
				</span>
			</summary>
			<p className='text faq-item__answer'>{answer}</p>
		</details>
	);
};

export default FaqItem;
