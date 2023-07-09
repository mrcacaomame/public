#include "aes/aes.h"
#include "base64/base64.h"

#define MSG_MAX_SIZE (2048)
#define KEY_SIZE     (64)
#define IV_SIZE      (32)
#define TAG_SIZE     (32)

int main (int argc, char *argv[]){
    if (argc != 5){
        fprintf(stderr, "\033[1;31mPut password as the first argument, key as the second argument, iv as the third argument, and tag as the fourth argument.\033[0m\n");
        return -1;
    }
    if (strlen (argv[1]) >= MSG_MAX_SIZE){
        fprintf (stderr, "\033[1;31mIncorrect password length. The maximum length is %d.\033[0m\n", MSG_MAX_SIZE);
        return -1;
    }
    if (strlen (argv[2]) >= KEY_SIZE){
        fprintf (stderr, "\033[1;31mIncorrect key length. The maximum length is %d.\033[0m\n", KEY_SIZE);
        return -1;
    }
    if (strlen (argv[3]) >= IV_SIZE){
        fprintf (stderr, "\033[1;31mIncorrect iv length. The maximum length is %d.\033[0m\n", IV_SIZE);
        return -1;
    }
    if (strlen (argv[4]) >= TAG_SIZE){
        fprintf (stderr, "\033[1;31mIncorrect tag length. The maximum length is %d.\033[0m\n", TAG_SIZE);
        return -1;
    }
    try{
        std::vector <uint8_t> password, key, iv, tag, out;
        base64::decode (argv[1], password);
        base64::decode (argv[2], key);
        base64::decode (argv[3], iv);
        base64::decode (argv[4], tag);

        common_key::aes_gcm_decrypt (password, key, iv, tag, out);

        for (const char &c : out){
            printf ("%c", c);
        }
        printf ("\n");
    }catch (const common_key_exception &e){
        fprintf (stderr, "%s\n", e.what ());
        return -1;
    }
    return 0;
}