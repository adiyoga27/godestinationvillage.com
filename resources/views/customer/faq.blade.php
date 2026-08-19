@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Frequently Asked Questions') }}"
    subtitle="{{ __('Everything you need to know about GODEVI, booking, payment and cancellations.') }}"
    image="assets/customer/img/page-title-area/faq.jpg"
    :crumbs="[__('Home') => '/', __('FAQ') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @php
            $groups = [
                [
                    'title' => 'General Questions',
                    'items' => [
                        ['q' => 'What is GODEVI?', 'a' => 'At GODEVI, we help you discover the best things to do in tourism villages across Indonesia. Find inspiration for your next travel adventure — from nature attractions to authentic local experiences. We empower the hosts from tourism villages to guide you with rich local information, offering the best prices with world-class customer support. GODEVI is a tourism company under PT Banua Wisata Lestari, and stands for "Go Destination Village". The GODEVI logo is inspired by the Bali Starling — a rare and unique natural potential — representing our hope to become a distinctive brand without losing the identity of Bali, always prioritizing the spirit of SEE (Sustainability, Empowerment, Entrepreneurship).'],
                        ['q' => 'How can I trust the experiences and hosts? Does GODEVI screen hosts?', 'a' => 'We take guests\' security and quality of experiences very seriously. At GODEVI, we curate our experiences by pre-screening both our hosts and the experiences they offer. We also provide training to the tourism village — meeting them in person to train what they need and learn what they have to offer. You can get to know our hosts better by reading their bios or checking reviews left on our website.'],
                        ['q' => 'Can you help me create an itinerary for my travel plan?', 'a' => 'We are happy to recommend some of our many existing activities in tourism villages that may suit your plan. However, we are not able to help you with planning your full itinerary. If you are interested in tailor-made or customized tours, please visit our customized tour page.'],
                        ['q' => 'What happens if something goes wrong during the experience?', 'a' => 'If you encounter any issue with your booking and are unable to resolve it with your host, please contact us at hellogodevi@gmail.com within 12 hours of the start of the experience. GODEVI collects your payment securely and holds onto it until 12 hours after the experience starts — so if anything goes wrong, you can simply contact us without worrying that your host has already been paid.'],
                        ['q' => 'How can I contact GODEVI?', 'a' => 'You can message, call, and chat with us! View our contact page to get in touch with us.'],
                    ],
                ],
                [
                    'title' => 'GODEVI Social Responsibility',
                    'items' => [
                        ['q' => 'What is important to Socially Responsible Tourism?', 'a' => 'GODEVI is a socially pro-active business dedicated to uplifting impoverished communities in developing villages through the tourism industry. Besides supporting fair trade, we create a marketplace by empowering village communities. We adhere to a strict policy of promoting Socially Responsible Village Tourism: (1) Collaborate with local village communities — we look for unique places run by locals that create jobs, contributing to the local economy. (2) Commitment to the environment — we learn together about how to care for the planet. (3) Preserving local culture — GODEVI creates moments to experience the real people and culture of your destination, from meals with local families to exploring religion, art and daily village life.'],
                    ],
                ],
                [
                    'title' => 'Booking',
                    'items' => [
                        ['q' => 'How do I find out the availability and price of an activity?', 'a' => 'On the GODEVI activity page, click "Book Now" and you will be asked to confirm your order by filling in customer and booking information. Select the number of participants and check availability by clicking the "Select Date" box. Available dates are shown in white and are clickable. You can see the total price at the bottom above "Book Now".'],
                        ['q' => 'How do I make a booking on GODEVI?', 'a' => 'Go to www.godestinationvillage.com and choose the activities and products we offer. Click "Book Now", review your order and enter your personal and booking information. You will receive an email about your order that you must pay — click "order details" to continue filling in complete information and proof of payment. You can pay via bank transfer, Visa or PayPal. Please note the payment receipt is not a confirmation email; our booking team will send an invoice after we receive your order.'],
                        ['q' => 'What is included in my booking?', 'a' => 'You can view what is included and not included in your booking on the activity page, including descriptions of related activities, the itinerary and important information regarding the activities.'],
                        ['q' => 'Is the price per group or per person?', 'a' => 'Prices depend on the activity you choose and are listed per person on the activity page. Click "Book Now" and add the number of participants to see the difference — if the total stays the same it is a group price, if it multiplies it is per person.'],
                        ['q' => 'How do I know if my booking is confirmed?', 'a' => 'If your booking request is received, you will receive an email confirmation with the full itinerary, your host\'s contact information, and any records the host has shared. GODEVI holds your payment up to 12 hours after the experience begins, so if something goes wrong you can contact us.'],
                        ['q' => 'How long does it take to receive a confirmation?', 'a' => 'Once you have paid and sent the confirmation form with proof of payment, your request is received automatically. After our booking team accepts your order we confirm it immediately. The host responds within a few hours, but has up to 72 hours (or until your chosen date/time, whichever is faster) to respond.'],
                        ['q' => 'Can I amend or change my booking?', 'a' => 'We understand plans change. For most activities it is possible to make changes before the cancellation due date — please refer to the "Cancellation Policy" of your booking and message the host via your Dashboard. Unfortunately we do not accept bookings over the phone.'],
                        ['q' => 'How can I contact the host?', 'a' => 'For general questions about an activity, you can send a message directly to an approved contact via phone or message on the web. Host details (name, email, phone) are shared when your order is received and requested by our booking team.'],
                    ],
                ],
                [
                    'title' => 'Payment',
                    'items' => [
                        ['q' => 'How can I pay for my booking?', 'a' => 'You can pay securely using bank transfer, Mastercard, Amex, Visa, or PayPal.'],
                        ['q' => 'Can I pay by cash?', 'a' => 'We accept cash on arrival, but you must pay a down payment of 30% of the total price.'],
                        ['q' => 'When will my card be charged?', 'a' => 'Your card will be charged once you have completed your booking. If your booking request is declined, you will receive a full refund.'],
                        ['q' => 'Are my credit card details safe?', 'a' => 'Your payment details are fully secure. All data is encrypted and transmitted securely with an SSL protocol.'],
                        ['q' => 'What is the GODEVI service fee?', 'a' => 'Our service fee covers our team handling your order — contacting host villages, tour guides and partners to make your booking successful.'],
                    ],
                ],
                [
                    'title' => 'Cancellations',
                    'items' => [
                        ['q' => 'Can I cancel my booking and how do I do it?', 'a' => 'We know plans can change. Before cancelling, we suggest contacting your host — they are very accommodating and may adjust to your schedule for a simple change of date or time. If you still need to cancel, go to Dashboard > Your Bookings, find your experience, and use the cancel button along with a reminder of the cancellation policy.'],
                        ['q' => 'Will I receive a refund after I cancel my booking?', 'a' => 'The experience\'s cancellation policy determines whether you receive a full refund. You are entitled to a full refund if your booking is still in review or you cancel before the cancellation due date.'],
                        ['q' => 'How long does it take to receive a refund in my bank account?', 'a' => 'The process usually takes 5–10 business days.'],
                        ['q' => 'I didn\'t mean to cancel my booking, what should I do?', 'a' => 'Please contact us immediately via dashboard message, email or phone so we can change your status accordingly.'],
                        ['q' => 'What is GODEVI\'s cancellation policy?', 'a' => 'Each experience has its own cancellation policy, which you can view on the activity page. Your experience is only officially cancelled when you receive an email confirming the cancellation and your refund status.'],
                    ],
                ],
            ];
        @endphp

        @foreach ($groups as $group)
            <div class="mb-12">
                <h2 class="mb-6 font-display text-2xl font-bold text-ink-950">{{ __($group['title']) }}</h2>
                <div class="space-y-4">
                    @foreach ($group['items'] as $item)
                        <details class="group rounded-2xl border border-ink-100 bg-white shadow-soft transition open:border-brand-200">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-ink-900 marker:hidden">
                                <span>{{ __($item['q']) }}</span>
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-open:rotate-45">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </span>
                            </summary>
                            <div class="border-t border-ink-100 px-6 py-5">
                                <p class="text-sm leading-relaxed text-ink-600">{{ __($item['a']) }}</p>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="card flex flex-col items-center gap-4 p-8 text-center sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h3 class="font-display text-lg font-bold text-ink-950">{{ __('Still have questions?') }}</h3>
                <p class="mt-1 text-sm text-ink-500">{{ __('Our team is happy to help you plan the perfect village experience.') }}</p>
            </div>
            <a href="{{ url('contact') }}" class="btn btn-primary shrink-0">{{ __('Contact Us') }}</a>
        </div>
    </div>
</section>
@endsection