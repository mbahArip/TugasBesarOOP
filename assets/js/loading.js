function hideScroll() {
    const loading_body = document.querySelector('body');
    
    loading_body.style.overflow = "hidden";
}

async function hideLoading() {
    const loading_body = document.querySelector('body');
    const loading_wrapper = document.querySelector('.loading');

    await sleep(1000);
    loading_wrapper.style.transition = "all 1s";
    loading_wrapper.style.transform = "translate(0, -100vh)";
    await sleep(1200);
    loading_wrapper.style.display = "none";
    loading_body.style.overflow = "auto";
}

hideScroll();
document.addEventListener('load', hideLoading());
// destroyLoading();