#ifndef __MODULES_COMMON_KEY_AES_EXCEPTION_H__
#define __MODULES_COMMON_KEY_AES_EXCEPTION_H__
#include <exception>
class common_key_exception : public std::exception{
    private: const char *msg;
    public: common_key_exception (const char *msg) noexcept;
    public: const char *what (void) const noexcept;
};
#endif
