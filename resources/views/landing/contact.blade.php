@extends('layouts.landing')

@section('title', 'Contact Us | RÉUTILISER')

@section('content')
<main class="max-w-[1600px] mx-auto px-8 md:px-16 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-32 items-start py-24 md:py-48">
        <!-- Text Content -->
        <div class="space-y-24 reveal-item">
            <header class="space-y-10">
                <p class="font-label-caps text-secondary tracking-[0.5em] text-sm opacity-60">CONNECT</p>
                <h1 class="font-display-lg text-primary leading-[0.9]">Personal Assistance<br/><span class="italic font-light"> & Inquiries</span></h1>
                <p class="font-body-lg text-secondary max-w-xl text-2xl leading-relaxed italic">Our concierge team is available to help with size guidance, artisanal provenance, or private appointments.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-20">
                <div class="space-y-4">
                    <h4 class="font-label-caps text-[11px] text-primary tracking-widest opacity-40">EMAIL US</h4>
                    <a href="mailto:concierge@reutiliser.com" class="font-body-md text-2xl text-secondary hover:text-primary transition-colors underline underline-offset-8">concierge@reutiliser.com</a>
                    <p class="text-sm text-secondary opacity-40">24-48h Response Time</p>
                </div>
                <div class="space-y-4">
                    <h4 class="font-label-caps text-[11px] text-primary tracking-widest opacity-40">CALL US</h4>
                    <p class="font-body-md text-2xl text-secondary">+33 (0) 1 23 45 67 89</p>
                    <p class="text-sm text-secondary opacity-40">MON-FRI / 9AM-6PM CET</p>
                </div>
            </div>

            <div class="bg-primary text-on-primary p-16 rounded-[3rem] reveal-item shadow-2xl">
                <p class="font-display-lg text-4xl mb-8 italic">"True luxury is found in the conversation between the piece and its new owner."</p>
                <p class="font-label-caps text-[10px] tracking-[0.4em] opacity-60">— THE COLLECTIVE</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-surface-container-low p-12 md:p-24 rounded-[4rem] reveal-item shadow-xl border border-primary/5">
            <h2 class="font-display-lg text-5xl text-primary mb-20">Send an Inquiry</h2>
            <form class="space-y-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    <div class="space-y-4">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase">Full Name</label>
                        <input class="w-full bg-white border-b-2 border-primary/10 py-6 px-0 focus:border-primary focus:outline-none transition-colors rounded-none bg-transparent text-xl" type="text" placeholder="Alex Vauthier"/>
                    </div>
                    <div class="space-y-4">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase">Email Address</label>
                        <input class="w-full bg-white border-b-2 border-primary/10 py-6 px-0 focus:border-primary focus:outline-none transition-colors rounded-none bg-transparent text-xl" type="email" placeholder="alex@vauthier.com"/>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase">Subject</label>
                    <select class="w-full bg-white border-b-2 border-primary/10 py-6 px-0 focus:border-primary focus:outline-none appearance-none cursor-pointer rounded-none bg-transparent text-xl">
                        <option>Product Information</option>
                        <option>Size Guidance</option>
                        <option>Sustainability Report</option>
                        <option>Press Inquiry</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase">Message</label>
                    <textarea class="w-full bg-white border-b-2 border-primary/10 py-6 px-0 focus:border-primary focus:outline-none transition-colors rounded-none bg-transparent text-xl min-h-[200px]" placeholder="Tell us about your inquiry..."></textarea>
                </div>

                <button class="w-full bg-primary text-on-primary py-8 rounded-full font-label-caps tracking-widest text-lg hover:bg-primary-container transition-all shadow-2xl">SUBMIT INQUIRY</button>
            </form>
        </div>
    </div>
</main>
@endsection
