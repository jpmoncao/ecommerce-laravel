export class UserUnauthorizedError extends Error {
    constructor(message: string) {
        super(message);
        this.name = "UserUnauthorizedError";
    }
}