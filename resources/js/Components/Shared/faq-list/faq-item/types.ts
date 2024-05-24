import { IFaq } from '@js/types/shared/faq';

export type FaqItemProps = Pick<IFaq, 'question' | 'answer'> & {
	className?: string;
};
