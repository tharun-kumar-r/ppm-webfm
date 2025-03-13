function validate(formId, fields, successCallback) {
    const validation = new JustValidate(formId);
    Object.entries(fields).forEach(([selector, rules]) => {
        const formattedRules = rules.map(rule => {
            return { ...rule };
        });
        validation.addField(selector, formattedRules);
    });
    validation.onSuccess(successCallback);
}

function validateRule(rulesConfig, errorIcon) {
    return Object.fromEntries(
        Object.entries(rulesConfig).map(([selector, rules]) => [
            selector,
            rules.map(ruleObj => ({
                ...ruleObj, 
                errorMessage: errorIcon + ruleObj.message
            }))
        ])
    );
}

const onReady = (callback) => document.addEventListener("DOMContentLoaded", callback);

