/**
 * Slideshow Class
 * Manages slideshow functionality including navigation and animations.
 * @export
 */
class ArtSlideshow {
    /**
     * Holds references to relevant DOM elements.
     * @type {Object}
     */
    DOM = {
        el: null, // Main slideshow container
        slides: null, // Individual slides
        slidesInner: null, // Inner content of slides (usually images)

        deco: null, // Empty deco element between the slides
    };
    /**
     * Index of the current slide being displayed.
     * @type {null}
     */
    current = 0;
    /**
     * Total number of slides.
     * @type {number}
     */
    slidesTotal = 0;

    /**
     * Flag to indicate if an animation is running.
     * @type {boolean}
     */
    isAnimating = false;

    /**
     * Slideshow constructor.
     * Initializes the slideshow and sets up the DOM elements.
     * @param {HTMLElement} DOM_el - The main element holding all the slides.
     */
    constructor(DOM_el) {
        // Initialize DOM elements
        this.DOM.el = DOM_el;
        this.DOM.slides = [...this.DOM.el.querySelectorAll('.as-art-slide')];
        this.DOM.slidesInner = this.DOM.slides.map((item) =>
            item.querySelector('.as-art-slide__img')
        );

        // Autoplay functionality
        this.DOM.autoplayInterval = this.DOM.el.dataset.interval; // Set autoplay interval in milliseconds (e.g., 5000ms = 5 seconds)
        this.DOM.enableAutoPlay = this.DOM.el.dataset.autoplay; // Set autoplay to true or false
        this.DOM.enableControls = this.DOM.el.dataset.controls; // Set controls to true or false
        this.DOM.enableIndicators = this.DOM.el.dataset.indicators; // Set controls to true or false
        this.DOM.type = this.DOM.el.dataset.type; // Set the type of slideshow (e.g., 'drape', 'theater')
        this.DOM.imageEffect = this.DOM.el.dataset.imageEffect; // Set the image effect (e.g., 'zoom in')
        this.DOM.imageEffectTween = [];
        this.DOM.autoplay = null;

        if (this.DOM.enableAutoPlay === '1') {
            this.DOM.autoplay = setInterval(() => this.next(), this.DOM.autoplayInterval);

            // Pause autoplay on user interaction
            this.DOM.el.querySelectorAll('.slides-nav__item--prev, .slides-nav__item--next').forEach((button) => {
                button.addEventListener('click', () => {
                    clearInterval(this.DOM.autoplay);
                    this.DOM.autoplay = setInterval(() => this.next(), this.DOM.autoplayInterval);
                });
            });
        }

        if (this.DOM.enableControls === 'true') {
            this.DOM.el.querySelector('.slides-nav__item--prev').addEventListener('click', () => this.prev());
            this.DOM.el.querySelector('.slides-nav__item--next').addEventListener('click', () => this.next());
        }

        if (this.DOM.enableIndicators === 'true') {
            // Add event listeners to indicators
            this.DOM.indicators = this.DOM.el.querySelectorAll('.as-art-slide-indicators button');
            this.DOM.indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    if (this.current !== index) {
                        if (this.DOM.enableAutoPlay === '1') clearInterval(this.DOM.autoplay);
                        // this.current = index === 0 ? this.slidesTotal - 1 : index - 1; // Set the current slide index
                        this.navigate(1, index); // Trigger navigation without direction
                        // this.DOM.indicators.forEach(btn => btn.classList.toggle('active', btn === indicator)); // Update active class
                        if (this.DOM.enableAutoPlay === '1') this.DOM.autoplay = setInterval(() => this.next(), this.DOM.autoplayInterval);
                    }
                });
            });
        }

        // Set initial slide as current
        this.DOM.slides[this.current].classList.add('slide--current');
        this.imageEffect(this.DOM.slidesInner[this.current].querySelector('.as-art-slide-img-inner'), this.current);

        // Count total slides
        this.slidesTotal = this.DOM.slides.length;

        // Deco elements
        this.DOM.deco = this.DOM.el.querySelectorAll('.deco');

        // Observer for touch/wheel navigation
        Observer.create({
            type: 'wheel,touch,pointer',
            onDown: () => {
                if (this.DOM.enableAutoPlay === '1') clearInterval(this.DOM.autoplay);
                this.prev();
                if (this.DOM.enableAutoPlay === '1') this.DOM.autoplay = setInterval(() => this.next(), this.DOM.autoplayInterval);
            },
            onUp: () => {
                if (this.DOM.enableAutoPlay === '1') clearInterval(this.DOM.autoplay);
                this.next();
                if (this.DOM.enableAutoPlay === '1') this.DOM.autoplay = setInterval(() => this.next(), this.DOM.autoplayInterval);
            },
            wheelSpeed: -1,
            tolerance: 10,
        });
    }

    /**
     * Navigate to the next slide.
     * @returns {void}
     */
    next() {
        this.navigate(1);
    }

    /**
     * Navigate to the previous slide.
     * @returns {void}
     */
    prev() {
        this.navigate(-1);
    }

    onStartPrepare(previous, current) {
        // Pause any ongoing image effects on the previous slide
        this.imageEffectPause(previous);
        // Add class to the upcoming slide to mark it as current
        this.DOM.slides[current].classList.add('slide--current');
        if (this.DOM.enableIndicators === 'true') {
            this.DOM.indicators[previous].classList.remove('active'); // Remove active class
            this.DOM.indicators[current].classList.add('active'); // Update active class
        }
    }

    onCompletePrepare(previous, current, upcomingInner) {
        // Apply image effect to the upcoming slide
        this.imageEffect(upcomingInner.querySelector('.as-art-slide-img-inner'), current);
        // Remove class from the previous slide to unmark it as current
        this.DOM.slides[previous].classList.remove('slide--current');
        // Reset animation flag
        this.isAnimating = false;
    }

    theater(previous, current, direction, index = null) {
        // Get the current and upcoming slides and their inner elements
        const currentSlide = this.DOM.slides[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        let timeline = gsap
            .timeline({
                defaults: {
                    duration: 0.8,
                    ease: 'power4.inOut',
                },
                onStart: () => {
                    this.imageEffectPause(previous);
                },
                onComplete: () => {
                    this.imageEffect(upcomingInner.querySelector('.as-art-slide-img-inner'), current);
                    // Reset animation flag
                    this.isAnimating = false;
                },
            })
            // Defining animation steps
            .addLabel('start', 0);

        [...this.DOM.deco].forEach((_, pos, arr) => {
            const deco = arr[arr.length - 1 - pos];
            timeline.fromTo(
                deco,
                {
                    xPercent: (_) => (pos % 2 === 1 ? -100 : 100),
                    autoAlpha: 1,
                },
                {
                    xPercent: (_) => (pos % 2 === 1 ? -50 : 50),
                    onComplete: () => {
                        if (pos === arr.length - 1) {
                            // Remove class from the previous slide to unmark it as current
                            this.DOM.slides[previous].classList.remove('slide--current');
                            if (this.DOM.enableIndicators === 'true') {
                                this.DOM.indicators.forEach(btn => btn.classList.remove('active')); // Update active class
                                this.DOM.indicators[this.current].classList.add('active'); // Update active class
                            }
                            // Add class to the upcoming slide to mark it as current
                            this.DOM.slides[current].classList.add('slide--current');
                        }
                    },
                },
                `start+=${Math.floor((arr.length - 1 - pos) / 2) * 0.14}`
            );
            if (!pos) {
                timeline.addLabel('middle', '>');
            }
        });

        timeline.to(
            currentSlide,
            {
                ease: 'power4.in',
                scale: 0.1,
                onComplete: () => {
                    currentSlide.style.transform = 'scale(1)';
                },
            },
            'start'
        );

        [...this.DOM.deco].forEach((_, pos, arr) => {
            const deco = arr[arr.length - 1 - pos];

            timeline.to(
                deco,
                {
                    xPercent: (_) => (pos % 2 === 1 ? -100 : 100),
                },
                `middle+=${Math.floor(pos / 2) * 0.12}`
            );
        });

        timeline.fromTo(
            upcomingSlide,
            {
                scale: 0.6,
            },
            {
                duration: 1.1,
                ease: 'expo',
                scale: 1,
            },
            '>-0.8'
        );

        return timeline;
    }

    drape(previous, current, direction, index = null) {
        // Get the current and upcoming slides and their inner elements
        const currentSlide = this.DOM.slides[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap.timeline({
            defaults: {
                duration: 1.25,
                ease: 'power4.inOut',
            },
            onStart: () => {
                this.onStartPrepare(previous, current);
                gsap.set(upcomingSlide, { zIndex: 1 });
            },
            onComplete: () => {
                this.onCompletePrepare(previous, current, upcomingInner);
                gsap.set(upcomingSlide, { zIndex: 0 });
            },
        })
            .addLabel('start', 0)
            .to(currentSlide, {
                duration: 0.4,
                ease: 'sine.inOut',
                scale: 0.9,
                autoAlpha: 0.2,
            }, 'start')
            .to(currentSlide, {
                yPercent: -direction * 20,
                autoAlpha: 0,
            }, 'start+=0.1')
            .fromTo(upcomingSlide, {
                autoAlpha: 1,
                scale: 1,
                yPercent: direction * 100,
            }, {
                yPercent: 0,
            }, 'start+=0.1')
            .fromTo(upcomingInner, {
                yPercent: -direction * 50,
            }, {
                yPercent: 0,
            }, 'start+=0.1');
    }

    slide_vertical(previous, current, direction, index = null) {
        // Get the current and upcoming slides and their inner elements
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        // Animation sequence using GSAP
        return gsap
            .timeline({
                defaults: {
                    duration: 1.3,
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    duration: 0.4,
                    ease: 'power2.in',
                    yPercent: -direction * 100,
                },
                'start'
            )
            .to(
                currentInner,
                {
                    duration: 0.4,
                    ease: 'power2.in',
                    yPercent: direction * 75,
                    rotation: -direction * 2,
                },
                'start'
            )
            .fromTo(
                this.DOM.deco,
                {
                    yPercent: direction * 100,
                    autoAlpha: 1,
                },
                {
                    duration: 0.4,
                    ease: 'power2.in',
                    yPercent: 0,
                },
                'start'
            )

            .addLabel('middle', 'start+=0.5')
            .to(
                this.DOM.deco,
                {
                    ease: 'expo',
                    yPercent: -direction * 100,
                },
                'middle'
            )
            .fromTo(
                upcomingSlide,
                {
                    autoAlpha: 1,
                    yPercent: direction * 100,
                },
                {
                    ease: 'expo',
                    yPercent: 0,
                },
                'middle'
            )
            .fromTo(
                upcomingInner,
                {
                    yPercent: -direction * 75,
                    rotation: direction * 2,
                },
                {
                    ease: 'expo',
                    yPercent: 0,
                    rotation: 0,
                },
                'middle'
            );
    }

    slide_horizontal(previous, current, direction, index = null) {
        // Get the current and upcoming slides and their inner elements
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        let timeline = gsap
            .timeline({
                defaults: {
                    duration: 1.2,
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    duration: 0.4,
                    ease: 'power2.in',
                    xPercent: -direction * 100,
                },
                'start'
            )
            .to(
                currentInner,
                {
                    duration: 0.4,
                    ease: 'power2.in',
                    xPercent: direction * 75,
                    rotation: -direction * 6,
                },
                'start'
            )
            .fromTo(
                this.DOM.deco,
                {
                    xPercent: direction * 100,
                    autoAlpha: 1,
                },
                {
                    duration: 0.4,
                    ease: 'power2.in',
                    xPercent: 0,
                },
                'start'
            );
        [...this.DOM.deco].forEach((_, pos, arr) => {
            timeline.to(
                arr[arr.length - 1 - pos],
                {
                    ease: 'power4',
                    xPercent: -direction * 100,
                },
                `start+=${(pos + 1) * 0.2}`
            );
        });
        timeline
            .addLabel('middle', '<')
            .fromTo(
                upcomingSlide,
                {
                    autoAlpha: 1,
                    xPercent: direction * 100,
                },
                {
                    ease: 'power4',
                    xPercent: 0,
                },
                'middle'
            )
            .fromTo(
                upcomingInner,
                {
                    xPercent: -direction * 75,
                    rotation: direction * 6,
                },
                {
                    ease: 'power4',
                    xPercent: 0,
                    rotation: 0,
                },
                'middle'
            );

        return timeline;
    }

    fashion(previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1.1,
                    ease: 'power2.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    scale: 0.6,
                    yPercent: -direction * 90,
                    rotation: direction * 20,
                    autoAlpha: 0,
                },
                'start'
            )
            .fromTo(
                upcomingSlide,
                {
                    scale: 0.8,
                    yPercent: direction * 100,
                    rotation: 0,
                    autoAlpha: 1,
                },
                {
                    scale: 1,
                    yPercent: 0,
                },
                'start+=0.1'
            )
            .fromTo(
                upcomingInner,
                {
                    scale: 1.1,
                },
                {
                    scale: 1,
                },
                'start+=0.1'
            );
    }

    shutters(previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1.6,
                    ease: 'power3.inOut'
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                }
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(currentSlide, {
                xPercent: -direction * 100
            }, 'start')
            .to(currentInner, {
                startAt: { transformOrigin: direction === 1 ? '100% 50%' : '0% 50%' },
                scaleX: 4
            }, 'start')
            .fromTo(upcomingSlide, {
                xPercent: direction * 100
            }, {
                xPercent: 0
            }, 'start')
            .fromTo(upcomingInner, {
                transformOrigin: direction === 1 ? '0% 50%' : '100% 50%',
                xPercent: -direction * 100,
                scaleX: 4
            }, {
                xPercent: 0,
                scaleX: 1
            }, 'start');
    }

    expo(previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];

        return gsap
            .timeline({
                defaults: {
                    duration: 1.2,
                    ease: 'expo',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                    gsap.set(upcomingSlide, { zIndex: 1 });
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                    gsap.set(upcomingSlide, { zIndex: 0 });
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    ease: 'power2',
                    autoAlpha: 0,
                },
                'start'
            )
            .fromTo(
                upcomingSlide,
                {
                    autoAlpha: 1,
                    scale: 0,
                    yPercent: direction * 100,
                },
                {
                    scale: 1,
                    yPercent: 0,
                },
                'start'
            )
            .fromTo(
                upcomingInner,
                {
                    scale: 2,
                    filter: 'brightness(600%)',
                },
                {
                    scale: 1,
                    filter: 'brightness(100%)',
                },
                'start'
            );
    }

    gallery(previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                onStart: () => {
                    this.onStartPrepare(previous, current);
                    gsap.set(upcomingSlide, { zIndex: 1 });
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                    gsap.set(upcomingSlide, { zIndex: 0 });
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .fromTo(
                upcomingSlide,
                {
                    autoAlpha: 1,
                    scale: 0.1,
                    xPercent: direction * 100,
                },
                {
                    duration: 0.7,
                    ease: 'expo',
                    scale: 0.4,
                    xPercent: 0,
                },
                'start'
            )
            .fromTo(
                upcomingInner,
                {
                    filter: 'contrast(100%) saturate(100%)',
                    transformOrigin: '100% 50%',
                    scaleX: 4,
                },
                {
                    duration: 0.7,
                    ease: 'expo',
                    scaleX: 1,
                },
                'start'
            )
            .fromTo(
                currentInner,
                {
                    filter: 'contrast(100%) saturate(100%)',
                },
                {
                    duration: 0.7,
                    ease: 'expo',
                    filter: 'contrast(120%) saturate(140%)',
                },
                'start'
            )

            .addLabel('middle', 'start+=0.6')
            .to(
                upcomingSlide,
                {
                    duration: 1,
                    ease: 'power4.inOut',
                    scale: 1,
                },
                'middle'
            )
            .to(
                currentSlide,
                {
                    duration: 1,
                    ease: 'power4.inOut',
                    scale: 0.98,
                    autoAlpha: 0,
                },
                'middle'
            );
    }

    tv_channel(previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];

        return gsap
            .timeline({
                defaults: {
                    duration: 0.8,
                    ease: 'power3.inOut',
                },
                onComplete: () => {
                    // Reset animation flag
                    this.isAnimating = false;
                },
            })
            // Defining animation steps
            .addLabel('start', 0)

            .fromTo(
                this.DOM.deco,
                {
                    yPercent: (pos) => (pos ? -100 : 100),
                    autoAlpha: 1,
                },
                {
                    yPercent: (pos) => (pos ? -50 : 50),
                },
                'start'
            )
            .to(
                currentSlide,
                {
                    scale: 1.1,
                    rotation: direction * 2,
                },
                'start'
            )

            .addLabel('middle', '>')
            .add(() => {
                // Remove class from the previous slide to unmark it as current
                this.DOM.slides[previous].classList.remove('slide--current');
                this.onStartPrepare(previous, current);
                this.imageEffect(upcomingInner.querySelector('.as-art-slide-img-inner'), current);
            }, 'middle')
            .to(
                this.DOM.deco,
                {
                    duration: 1.1,
                    ease: 'expo',
                    yPercent: (pos) => (pos ? -100 : 100),
                },
                'middle'
            )

            .fromTo(
                upcomingSlide,
                {
                    scale: 1.1,
                    rotation: direction * 2,
                },
                {
                    duration: 1.1,
                    ease: 'expo',
                    scale: 1,
                    rotation: 0,
                },
                'middle'
            );
    }

    flip (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];

        return gsap
            .timeline({
                defaults: {
                    duration: 1.2,
                    ease: 'power3.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                    gsap.set(upcomingSlide, { zIndex: 1 });
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                    gsap.set(upcomingSlide, { zIndex: 0 });
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    yPercent: -direction * 100,
                },
                'start'
            )
            .fromTo(
                upcomingSlide,
                {
                    yPercent: 0,
                    autoAlpha: 0,
                    rotationX: 140,
                    scale: 0.1,
                    z: -1000,
                },
                {
                    autoAlpha: 1,
                    rotationX: 0,
                    z: 0,
                    scale: 1,
                },
                'start+=0.1'
            )
            .fromTo(
                upcomingInner,
                {
                    scale: 1.8,
                },
                {
                    scale: 1,
                },
                'start+=0.17'
            );
    }

    stone_throwing (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1.2,
                    ease: 'expo',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                    gsap.set(upcomingSlide, { zIndex: 1 });
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                    gsap.set(upcomingSlide, { zIndex: 0 });
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    duration: 1,
                    ease: 'power4',
                    rotation: 500,
                    scale: 0,
                    xPercent: -20,
                    onComplete: () =>
                        gsap.set(currentSlide, {
                            rotation: 0,
                            scale: 1,
                            autoAlpha: 0,
                        }),
                },
                'start'
            )
            .fromTo(
                upcomingSlide,
                {
                    autoAlpha: 0,
                    scale: 1.4,
                    rotation: -90,
                    xPercent: 20,
                },
                {
                    duration: 0.6,
                    ease: 'power4',
                    autoAlpha: 1,
                    scale: 1,
                    rotation: 0,
                    xPercent: 0,
                },
                'start+=0.3'
            );
    }

    spring (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1.1,
                    ease: 'power2.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                    gsap.set(upcomingSlide, { zIndex: 1 });
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                    gsap.set(upcomingSlide, { zIndex: 0 });
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    scale: 0.4,
                },
                'start'
            )
            .to(
                currentInner,
                {
                    scale: 1.5,
                },
                'start'
            )
            .addLabel('middle', 'start+=0.65')
            .fromTo(
                upcomingSlide,
                {
                    yPercent: direction * 100,
                    scale: 1,
                },
                {
                    duration: 1,
                    ease: 'expo',
                    yPercent: 0,
                },
                'middle'
            )
            .fromTo(
                upcomingInner,
                {
                    scale: 1.5,
                    yPercent: -direction * 30,
                },
                {
                    duration: 1.1,
                    ease: 'expo',
                    scale: 1,
                    yPercent: 0,
                },
                'middle'
            );
    }

    slide_cross (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1,
                    ease: 'power3.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                    gsap.set(upcomingSlide, { zIndex: 1 });
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                    gsap.set(upcomingSlide, { zIndex: 0 });
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    xPercent: -direction * 15,
                    yPercent: -direction * 15,
                    autoAlpha: 0,
                },
                'start'
            )

            .fromTo(
                upcomingSlide,
                {
                    autoAlpha: 1,
                    xPercent: direction * 100,
                    yPercent: direction * 100,
                },
                {
                    xPercent: 0,
                    yPercent: 0,
                },
                'start'
            );
    }

    simple_slide_vertical (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];

        return gsap
            .timeline({
                defaults: {
                    duration: 1.5,
                    ease: 'power4.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    yPercent: -direction * 100,
                },
                'start'
            )
            .to(
                currentInner,
                {
                    yPercent: direction * 30,
                },
                'start'
            )
            .fromTo(
                upcomingSlide,
                {
                    yPercent: direction * 100,
                },
                {
                    yPercent: 0,
                },
                'start'
            )
            .fromTo(
                upcomingInner,
                {
                    yPercent: -direction * 30,
                    //yPercent: 0
                },
                {
                    yPercent: 0,
                },
                'start'
            );
    }

    shutters_vertical (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1.5,
                    ease: 'power4.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    yPercent: -direction * 100,
                },
                'start'
            )
            .to(
                currentInner,
                {
                    yPercent: direction * 30,
                    startAt: {
                        transformOrigin: direction === 1 ? '0% 100%' : '100% 0%',
                        rotation: 0,
                    },
                    rotation: -direction * 10,
                    scaleY: 2.5,
                },
                'start'
            )
            .to(
                upcomingSlide,
                {
                    startAt: {
                        yPercent: direction * 100,
                    },
                    yPercent: 0,
                },
                'start'
            )
            .to(
                upcomingInner,
                {
                    startAt: {
                        transformOrigin: direction === 1 ? '0% 0%' : '100% 100%',
                        yPercent: -direction * 30,
                        scaleY: 2.5,
                        rotation: -direction * 10,
                    },
                    yPercent: 0,
                    scaleY: 1,
                    rotation: 0,
                },
                'start'
            );
    }

    page_flip (previous, current, direction, index = null) {
        const currentSlide = this.DOM.slides[previous];
        const currentInner = this.DOM.slidesInner[previous];
        const upcomingSlide = this.DOM.slides[current];
        const upcomingInner = this.DOM.slidesInner[current];
        return gsap
            .timeline({
                defaults: {
                    duration: 1.2,
                    ease: 'power4.inOut',
                },
                onStart: () => {
                    this.onStartPrepare(previous, current);
                },
                onComplete: () => {
                    this.onCompletePrepare(previous, current, upcomingInner);
                },
            })
            // Defining animation steps
            .addLabel('start', 0)
            .to(
                currentSlide,
                {
                    startAt: {
                        transformOrigin: direction === 1 ? '0% 50%' : '100% 50%',
                    },
                    scaleX: 0,
                    autoAlpha: 0,
                },
                'start'
            )
            .fromTo(
                upcomingSlide,
                {
                    transformOrigin: direction === 1 ? '100% 50%' : '0% 50%',
                    autoAlpha: 0,
                    scaleX: 0,
                },
                {
                    autoAlpha: 1,
                    scaleX: 1,
                },
                'start'
            );
    }

    imageEffectPause(index = 0) {
        // Pause the image effect tween if it exists
        if (this.DOM.imageEffect !== '' && typeof this.DOM.imageEffectTween[index] !== 'undefined') {
            this.DOM.imageEffectTween[index].pause();
        }
    }

    imageEffect(element, index = 0) {
        // Apply image effect if defined
        if (this.DOM.imageEffect === 'zoom_image') {
            if (typeof this.DOM.imageEffectTween[index] === 'undefined') {
                this.DOM.imageEffectTween[index] = gsap.fromTo(element, {scale: 1}, {
                    scale: 1.2,
                    duration: 8,
                    ease: 'sine.in',
                    yoyo: true,
                    repeat: 1
                });
            } else {
                this.DOM.imageEffectTween[index].play();
            }
        }
    }

    /**
     * Navigate through slides.
     * @param {number} direction - The direction to navigate. 1 for next and -1 for previous.
     * @returns {boolean} - Return false if the animation is currently running.
     */
    navigate(direction, index = null) {
        // Check if animation is already running
        if (this.isAnimating) return false;
        this.isAnimating = true;
        // Update the current slide index based on direction
        const previous = this.current;
        if (index !== null) {
            // If an index is provided, set current to that index
            this.current = index;
            direction = (index > previous) ? 1 : -1; // Determine direction based on index
        } else {
            this.current =
                direction === 1
                    ? this.current < this.slidesTotal - 1
                        ? ++this.current
                        : 0
                    : this.current > 0
                        ? --this.current
                        : this.slidesTotal - 1;
        }

        // Get the current and upcoming slides and their inner elements
        const upcomingSlide = this.DOM.slides[this.current];
        // Animation sequence using GSAP
        this.tl = this[this.DOM.type](previous, this.current, direction, index);
        const text = upcomingSlide.querySelector('.astroid-text-container');
        if (text) {
            this.tl.from(text, {
                opacity: 0,
                duration: 0.9,
                ease: "sine.out",
            }, '>-0.8');
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.as-art-slides').forEach(function(el) {
        new ArtSlideshow(el);
    });
})
