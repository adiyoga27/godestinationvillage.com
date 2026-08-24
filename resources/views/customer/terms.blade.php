@extends('customer/layout')

@section('content')

<x-partials.page-hero
    :title="__('Terms & Conditions')"
    subtitle="{{ __('Please read these terms of use carefully before using GODEVI services.') }}"
    image="assets/customer/img/page-title-area/terms.jpg"
    :crumbs="[__('Home') => '/', __('Terms & Conditions') => '']"
/>

<section class="section-pad">
    <div class="container-gd max-w-4xl">
        <div class="card p-8 sm:p-12">
            <div class="prose-gd text-sm leading-relaxed">
                <h2 class="font-display !text-2xl">{{ __('1. Your Agreement') }}</h2>
                <p>1.1 This website ("This Website") is operated by GODEVI "Go Destination Villages", a company under the auspices of PT Banua Wisata Lestari — a tourism travel business that specifically offers tourism activities in Tourism Villages and other tourist activities. Please read these terms of use ("These Terms of Use") carefully before using this Website and the services offered by GODEVI, its affiliated companies (together, "GODEVI") or third party operators ("Operators") through this Website ("Service"). "You" and "yours" when used in these Terms of Use include (1) anyone who accesses the Website and (2) the person to whom you purchased the Service.</p>

                <h2 class="font-display !text-2xl">{{ __('2. Change of Terms of Use') }}</h2>
                <p>2.1 GODEVI's Modifications</p>
                <p>2.1.1 GODEVI reserves the right, in its sole discretion, to change or modify any part of these Terms of Use at any time without prior notice. You must visit this page periodically to review the current Terms of Use that bind you. If GODEVI changes or modifies these Terms of Use, GODEVI will post the changes on this page and show at the bottom the date on which they were last revised.</p>
                <p>2.1.2 Your continued use of this Website after any such changes constitutes your acceptance of the revised Terms of Use. If you do not agree to abide by the revised Terms of Use, do not use or access this Website and/or the Services.</p>
                <p>2.1.3 When using the Services, you shall be subject to any additional terms applicable to such Services, including the privacy policy adopted by GODEVI. All such terms are hereby expressly incorporated by reference in these Terms of Use.</p>

                <h2 class="font-display !text-2xl">{{ __('3. Access and Use of the Services') }}</h2>
                <p>3.1 Ownership of Content — This website, domain names (www.godestinationvillage.com), subdomains, features, content and application services are offered by GODEVI in connection with those owned and operated by GODEVI.</p>
                <p>3.2 Provision and Accessibility of Services — GODEVI may offer the Service on its own or on behalf of the Operator. The services you choose are solely for your own use. GODEVI may change, suspend or terminate any Service at any time, and may impose restrictions or limit your access to parts or all Services without notice or liability.</p>
                <p>3.2.2 GODEVI does not guarantee that the Service will always be available or uninterrupted. You are responsible for making all necessary arrangements to access the Service and for ensuring that all people who access it through an Internet connection are aware of these Terms of Use.</p>
                <p>3.2.3 If you link to this website, GODEVI may revoke your right to link at any time, at its sole discretion.</p>

                <h2 class="font-display !text-2xl">{{ __('4. Website and Content') }}</h2>
                <p>4.1 Use of the Content — All material displayed on this Website including text, data, graphics, articles, photos, images, illustrations, videos, audio and other material ("Content") is protected by copyright and/or other intellectual property rights and is intended solely for your use of the Service and your non-commercial personal use.</p>
                <p>4.1.2 If GODEVI agrees to give you access, such access is a non-exclusive, non-transferable and limited license. GODEVI may, at its absolute discretion, change or delete the presentation, substance or function of any part or all Content from this Website.</p>
                <p>4.1.3 You shall not use, copy, reproduce, modify, translate, publish, broadcast, transmit, distribute, perform, upload, display, license, sell or otherwise exploit this Website or the Content without the express prior written consent of the respective owners.</p>
                <p>4.2 GODEVI's Liability for the Website and Content — GODEVI cannot guarantee the identity of any other users, nor the authenticity and accuracy of any content provided by other users or Operators. All Content is accessed at your own risk.</p>

                <h2 class="font-display !text-2xl">{{ __('5. Intellectual Property Rights') }}</h2>
                <p>All intellectual property rights subsisting in respect of this Website belong to GODEVI or have been licensed to GODEVI for use on this Website. You undertake that you shall not modify, publish, transmit, reproduce, create derivative works based on, distribute, perform, display or in any way exploit any part of this Website and the Content; you shall only download or copy the Content for personal and non-commercial use; and you shall not store any significant portion of any Content in any form.</p>

                <h2 class="font-display !text-2xl">{{ __('6. User Submissions') }}</h2>
                <p>By posting information or content on the Website or otherwise providing content, materials or information to GODEVI and/or the Operators ("User Submissions"), you grant GODEVI and the Operators a non-exclusive, worldwide, royalty free, perpetual, irrevocable, sub-licensable and transferable right to use and fully exploit such User Submissions. You represent and warrant that any content in your User Submission does not infringe any applicable laws, regulations or any third party rights.</p>

                <h2 class="font-display !text-2xl">{{ __('7. Users Representations, Warranties and Undertakings') }}</h2>
                <p>You represent, warrant and undertake to GODEVI that you will not use this Website or the Services in a manner that: (a) infringes any third party rights; (b) violates any law; (c) is harmful, fraudulent, deceptive, threatening, abusive, harassing, defamatory or otherwise objectionable; (d) involves commercial activities without GODEVI's prior written consent; (e) impersonates any person or entity; or (f) contains a virus or other harmful computer code. GODEVI reserves the right to remove any User Submissions at any time.</p>

                <h2 class="font-display !text-2xl">{{ __('8. Registration and Security') }}</h2>
                <p>As a condition to using some aspects of the Services, you may be required to register and select a password and user name. You shall provide accurate, complete and updated registration information. GODEVI reserves the right to refuse registration or cancel a GODEVI Account at its sole discretion. You are responsible for maintaining the confidentiality of your password.</p>

                <h2 class="font-display !text-2xl">{{ __('9. Reviews — Further Correspondence — Rights to User Content') }}</h2>
                <p>By completing a booking, you agree to receive confirmation messages and review invitations after you finish an activity. Leaving a review is optional. Upon submitting a review, your account may be awarded GODEVI credits subject to terms and conditions. By posting a review, you grant GODEVI the full, perpetual, free, transferable and irrevocable rights to all submitted user content. Reviews may not contain obscenities, hate speech, personal information of others or irrelevant content.</p>

                <h2 class="font-display !text-2xl">{{ __('10. Booking Confirmation, Tickets, Vouchers, Fees and Payment') }}</h2>
                <p>Certain Services are subject to instant confirmation. Through this Website you may purchase vouchers for the Services offered by the Operators. To use your Voucher you must appear in person at the designated meeting point on time and present the required documents. Vouchers are admission tickets to one-time events; unused vouchers will not be refunded unless expressly set forth. Cancelation windows vary on a case-by-case basis. If an Event is canceled by the Operator, GODEVI will process a full refund. Please contact GODEVI at hello@godevi.id for any required assistance.</p>

                <h2 class="font-display !text-2xl">{{ __('11. Discounts') }}</h2>
                <p>GODEVI Credits are points awarded and accumulated in your GODEVI member account until expiry. Every ten (10) GODEVI Credits may be used to offset IDR 2,000 of the total check out price. GODEVI Coupons are one-time use coupons sent to your email or applied directly to your account. GODEVI reserves the right to terminate accounts or cancel all credits and coupons earned in a fraudulent manner.</p>

                <h2 class="font-display !text-2xl">{{ __('12. Godevi Referral Program') }}</h2>
                <p>On certain GODEVI Sites you may earn GODEVI Coupons when you invite friends to become members and those friends make a confirmed booking through an authorized GODEVI channel. You may only earn GODEVI Coupons via GODEVI's authorized invite mechanisms. Having multiple GODEVI accounts is a violation of these Terms of Use. GODEVI reserves the right to modify or terminate the Referral Program at any time.</p>

                <h2 class="font-display !text-2xl">{{ __('13. Privacy Policy') }}</h2>
                <p>For GODEVI's policy relating to the use of your personal data, please review GODEVI's current Privacy Policy, which is incorporated by reference into these Terms of Use.</p>

                <h2 class="font-display !text-2xl">{{ __('14. Indemnity') }}</h2>
                <p>You will indemnify and hold GODEVI, our holding companies, subsidiaries, affiliates, officers, directors and employees harmless from all damages, liabilities, settlements, costs and attorney's fees arising out of your access to or use of this Website or your violation of these Terms of Use.</p>

                <h2 class="font-display !text-2xl">{{ __('15. Disclaimers and Limitation of Liability') }}</h2>
                <p>GODEVI has no control over the Operators or third parties and does not guarantee the safety of any transaction or the truth or accuracy of any listing. In no event will GODEVI be liable for any loss of profits or any indirect, consequential, special, incidental, or punitive damages. This Website is provided on an "as is" basis. To the fullest extent permissible by law, GODEVI's liability is limited to the amount of fees you paid in the twelve months prior to the action giving rise to liability, or IDR 1,000,000 in the aggregate for all claims.</p>

                <h2 class="font-display !text-2xl">{{ __('16. Interaction with Third Parties') }}</h2>
                <p>This Website may contain links to third party websites not owned or controlled by GODEVI. Links do not constitute endorsement. When you access third party websites you do so at your own risk. GODEVI shall not be responsible for any loss or damage incurred as a result of dealings with third parties.</p>

                <h2 class="font-display !text-2xl">{{ __('17. Payment') }}</h2>
                <p>In order to ensure adequate operational support for refunds and cancelations, the following GODEVI entities shall be responsible for transactions conducted in the following currencies: (a) SGD — GODEVI Travel Technology Pte Ltd; (b) TWD — GODEVI Travel Taiwan Limited; (c) MYR — GODEVI Technology Sdn. Bhd.; (d) EUR/GBP/CHF/DKK/ISK/NOK/SEK/RUB — GODEVI Travel Technology B.V.; and (e) all other currencies — GODEVI Travel Technology Limited.</p>

                <h2 class="font-display !text-2xl">{{ __('18. Termination') }}</h2>
                <p>These Terms of Use shall remain in full force and effect while you use this Website or the Services. GODEVI may terminate or suspend your access at any time for any reason, without notice. Upon termination, your right to use the Services will immediately cease, and all provisions which by their nature should survive termination shall survive.</p>

                <h2 class="font-display !text-2xl">{{ __('19. Passports, Visas & Insurances') }}</h2>
                <p>It is the responsibility of all passengers, regardless of nationality and destination, to check with the consulate of the country they are visiting for current entry requirements. GODEVI strongly recommends that you purchase a comprehensive travel insurance policy prior to departure.</p>

                <h2 class="font-display !text-2xl">{{ __('20. Governing Law') }}</h2>
                <p>These Terms of Use shall be governed by the laws of the Republic of Indonesia. You agree to submit to the non-exclusive jurisdiction of the Indonesian courts.</p>

                <h2 class="font-display !text-2xl">{{ __('21. Miscellaneous') }}</h2>
                <p>If any provision is found to be unenforceable or invalid, that provision shall be limited or eliminated to the minimum extent necessary. These Terms of Use are not assignable by you except with GODEVI's prior written consent. These Terms of Use have been drafted in the English language; in the event of inconsistency, the English language version shall always prevail.</p>

                <h2 class="font-display !text-2xl">{{ __('22. Contact') }}</h2>
                <p>Please contact GODEVI at hello@godevi.id to report any violations of these Terms of Use or to pose any questions regarding the Terms of Use or the Service.</p>
                <p class="!mt-8 text-xs font-semibold uppercase tracking-wider text-ink-400">{{ __('Last updated on 18th Jun 2019.') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection