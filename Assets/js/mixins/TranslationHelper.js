export default {
    methods: {
        trans(string, replacements = {}) {
            // Makes a string: core.button.cancel | core.button.created at
            // to: core["button.cancel"] | core["button.created at"]
            const array = string.split('.');

            if (array.length < 2) {
                return this.$t(string);
            }

            const first = array.splice(0, 1),
                key = array.join('.');

            let translatedMessage = this.$t(`${first}['${key}']`);

            for (const key in replacements) {
                translatedMessage = translatedMessage.replace(`:${key}`, replacements[key]);
            }

            return translatedMessage;
        },
    },
};
