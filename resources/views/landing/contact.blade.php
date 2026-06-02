@extends('layouts.landing')

@section('title', 'Contact Us | RÉUTILISER')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-start py-24 md:py-48">
        <!-- Text Content -->
        <div class="space-y-24 reveal-item">
            <header class="space-y-10">
                <p class="font-label-caps text-secondary tracking-[0.5em] text-sm opacity-60">CONNECT</p>
                <h1 class="font-display-lg text-primary leading-[0.9]">Personal Assistance<br/><span class="italic font-light"> & Inquiries</span></h1>
                <p class="font-body-lg text-secondary max-w-xl text-2xl leading-relaxed italic">Our concierge team is available to help with size guidance, artisanal provenance, or private appointments.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-20">
                <div class="space-y-4">
                    <h4 class="font-label-caps text-[11px] text-primary tracking-widest opacity-40 uppercase">Email Us</h4>
                    <a href="mailto:concierge@reutiliser.com" class="font-body-md text-2xl text-secondary hover:text-primary transition-colors underline underline-offset-8">concierge@reutiliser.com</a>
                    <p class="text-sm text-secondary opacity-40">24-48h Response Time</p>
                </div>
                <div class="space-y-4">
                    <h4 class="font-label-caps text-[11px] text-primary tracking-widest opacity-40 uppercase">Call Us</h4>
                    <p class="font-body-md text-2xl text-secondary">+33 (0) 1 23 45 67 89</p>
                    <p class="text-sm text-secondary opacity-40">MON-FRI / 9AM-6PM CET</p>
                </div>
            </div>

            <div class="bg-primary p-16 rounded-[3rem] reveal-item shadow-2xl">
                <p class="font-display-lg text-4xl mb-8 italic text-white">"True luxury is found in the conversation between the piece and its new owner."</p>
                <p class="font-label-caps text-[10px] tracking-[0.4em] opacity-80 uppercase text-white">— The Collective</p>
            </div>
        </div>

        <!-- Contact Form (Updated to match Footer Style) -->
        <div class="bg-surface-container-low p-12 md:p-20 rounded-[4rem] reveal-item shadow-xl border-0">
            <h2 class="font-display-lg text-5xl text-primary mb-20">Send an Inquiry</h2>
            <form class="space-y-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-4">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase ml-4">Full Name</label>
                        <div class="bg-white rounded-2xl shadow-sm p-2">
                            <input class="w-full bg-transparent border-none focus:ring-0 text-lg text-primary placeholder:text-secondary/30 px-4 py-3" type="text" placeholder="Alex Vauthier"/>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase ml-4">Email Address</label>
                        <div class="bg-white rounded-2xl shadow-sm p-2">
                            <input class="w-full bg-transparent border-none focus:ring-0 text-lg text-primary placeholder:text-secondary/30 px-4 py-3" type="email" placeholder="alex@vauthier.com"/>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase ml-4">Subject</label>
                    <div class="bg-white rounded-2xl shadow-sm p-2">
                        <select class="w-full bg-transparent border-none focus:ring-0 text-lg text-primary appearance-none cursor-pointer px-4 py-3">
                            <option>Product Information</option>
                            <option>Size Guidance</option>
                            <option>Sustainability Report</option>
                            <option>Press Inquiry</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase ml-4">Message</label>
                    <div class="bg-white rounded-3xl shadow-sm p-4">
                        <textarea class="w-full bg-transparent border-none focus:ring-0 text-lg text-primary placeholder:text-secondary/30 min-h-[200px] resize-none" placeholder="Tell us about your inquiry..."></textarea>
                    </div>
                </div>

                <button class="w-full bg-primary text-on-primary py-8 rounded-full font-label-caps tracking-[0.3em] text-lg font-bold hover:bg-primary-container transition-all shadow-2xl mt-8">SUBMIT INQUIRY</button>
            </form>
        </div>
    </div>
</main>
@endsection
