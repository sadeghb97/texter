/**
 * Simple modular toast notifications.
 * Usage: AppToast.success("Saved."); AppToast.show("Hello", { type: "info", duration: 3000 });
 */
(function (global) {
    const DEFAULT_DURATION = 4000;
    const EXIT_MS = 200;

    let stackEl = null;
    const timers = new WeakMap();

    function ensureStack() {
        if (stackEl?.isConnected) return stackEl;
        stackEl = document.createElement("div");
        stackEl.className = "app-toast-stack";
        stackEl.setAttribute("aria-live", "polite");
        stackEl.setAttribute("aria-relevant", "additions");
        document.body.appendChild(stackEl);
        return stackEl;
    }

    function dismiss(toastEl) {
        if (!toastEl?.isConnected) return;
        const timer = timers.get(toastEl);
        if (timer) clearTimeout(timer);
        toastEl.classList.remove("is-visible");
        toastEl.classList.add("is-leaving");
        setTimeout(() => toastEl.remove(), EXIT_MS);
    }

    function show(message, options = {}) {
        const text = String(message ?? "").trim();
        if (!text) return null;

        const type = options.type || "info";
        const duration = Number.isFinite(options.duration) ? options.duration : DEFAULT_DURATION;

        const toastEl = document.createElement("div");
        toastEl.className = `app-toast app-toast--${type}`;
        toastEl.setAttribute("role", type === "error" ? "alert" : "status");
        toastEl.textContent = text;

        const stack = ensureStack();
        stack.appendChild(toastEl);

        requestAnimationFrame(() => {
            requestAnimationFrame(() => toastEl.classList.add("is-visible"));
        });

        if (duration > 0) {
            const timer = setTimeout(() => dismiss(toastEl), duration);
            timers.set(toastEl, timer);
        }

        toastEl.addEventListener("click", () => dismiss(toastEl));
        return toastEl;
    }

    global.AppToast = {
        show,
        success: (message, options) => show(message, { ...options, type: "success" }),
        error: (message, options) => show(message, { ...options, type: "error" }),
        info: (message, options) => show(message, { ...options, type: "info" }),
        warning: (message, options) => show(message, { ...options, type: "warning" }),
        dismiss,
    };
})(typeof window !== "undefined" ? window : globalThis);
