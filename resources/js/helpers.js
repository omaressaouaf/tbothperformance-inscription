import dayjs from "dayjs";
import { FormatMoney } from "format-money-js";

/**
 * Fire confirmation modal
 * @param {Object} options
 * @param {string} [options.icon]
 * @param {string} [options.iconColor]
 * @param {string} options.title
 * @param {string} [options.description]
 * @param {number} [options.duration]
 * @param {boolean} [options.close]
 * @param {string} [options.gravity]
 * @param {string} [options.position]
 * @param {string} [options.destination]
 */
export const fireToast = (options) => {
    emitter.emit("fire-toast", options);
};

/**
 * Fire confirmation modal
 * @param {function} onDelete
 * @param {Object} [options]
 * @param {string} [options.icon]
 * @param {string} [options.title]
 * @param {string} [options.description]
 * @param {string} [options.textToEnter]
 */
export const fireConfirmationModal = (onDelete, options) => {
    emitter.emit("fire-confirmation-modal", { onDelete, options });
};

/**
 * Fire confirmation modal with text to enter and standard dangerous description text
 * @param {function} onDelete
 * @param {string} textToEnter
 */
export const fireTextConfirmationModal = (onDelete, textToEnter) => {
    const options = {
        description:
            __("This process cannot be undone.") +
            " " +
            __(
                "This will permanently delete the record and all of its resources"
            ),
        textToEnter,
    };

    fireConfirmationModal(onDelete, options);
};

/**
 * The callback executed after the attribute changes
 *
 * @callback attributeChangeCallback
 * @param {HTMLElement} node
 */

/**
 * Registers attribute change on given element
 * @param {HTMLElement} element
 * @param {string} attribute
 * @param {attributeChangeCallback} callback
 * @returns {void}
 */
export const onElementAttributeChange = (element, attribute, callback) => {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (
                mutation.type === "attributes" &&
                mutation.attributeName === attribute
            ) {
                callback(mutation.target);
            }
        });
    });

    observer.observe(element, { attributes: true });

    return observer.disconnect;
};

/**
 * Format date
 * @param {string | Date} date
 * @param {string} format
 * @returns {string}
 */
export const formatDate = (date, format = "MMMM D, YYYY") => {
    return dayjs(date).format(format);
};

/**
 * Format date time
 * @param {string | Date} date
 * @returns {string}
 */
export const formatDateTime = (date, onlyTime = false) => {
    return dayjs(date).format(onlyTime ? "LT" : "MMMM D, YYYY LT");
};

/**
 * Format date for input
 * @param {string | Date} date
 * @param {boolean} time
 * @returns {string}
 */
export const formatDateForInput = (date, time = false) => {
    return dayjs(date).format(time ? "YYYY-MM-DDTHH:mm" : "YYYY-MM-DD");
};

/**
 * Format money
 * @param {number} number
 * @returns {string}
 */
export const formatMoney = (number) => {
    return (
        new FormatMoney({ decimals: 2 }).from(Number(number)) + " " + __("DHS")
    );
};
