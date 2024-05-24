import React from 'react';
import { Head } from '@inertiajs/react';

import ContentHeader from '@js/Components/Shared/ContentHeader.jsx';
import { FaqList } from '@js/Components/Shared/faq-list';
import { FaqForm } from '@js/Components/Shared/faq-form';

const FaqsOverviewPage = ({ title, faqs, showQuestionForm }) => {
    return (
        <>
            <Head>
                <title>{title}</title>
            </Head>
            <ContentHeader title={title} />
            <div className='page-content__content-container separator-container'>
                <FaqList items={faqs} />
                {showQuestionForm && <FaqForm />}
            </div>
        </>
    );
};

export default FaqsOverviewPage;
