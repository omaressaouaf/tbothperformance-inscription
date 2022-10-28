import dayjs from "dayjs";
import { FormatMoney } from "format-money-js";

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
