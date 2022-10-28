import Form from "vform";

class VForm extends Form {
    constructor(data) {
        super(data);
    }

    /**
     * Fill the form data
     * @param {Record<string, any>} data
     */
    fill(data) {
        Object.keys(data).forEach((key) => {
            this[key] = data[key];
        });
    }

    /**
     * Determine if the form is dirty
     * @returns {boolean} is dirty
     */
    isDirty() {
        return !_.isEqual(this.data(), this.originalData);
    }

    /**
     * Submit the form data via an HTTP request
     * @param {string} method
     * @param {string} url
     * @param {object} config
     * @returns {Promise<AxiosResponse<T>>}
     */
    submit(method, url, config = {}) {
        return new Promise(async (resolve) => {
            config = {
                ...config,
                headers: {
                    ...config.headers,
                    "X-VForm": true,
                },
            };

            try {
                resolve(await super.submit(method, url, config));
            } catch {}
        });
    }
}

export default VForm;
