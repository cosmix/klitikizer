# Klitikizer

[![Build Status](https://travis-ci.org/cosmix/klitikizer.svg?branch=develop)](https://travis-ci.org/cosmix/klitikizer)

Klitikizer is a very simple class for PHP 7.1+ that returns the vocative case for Greek names, both first and surnames. Its aim is to eliminate the unsightly phenomenon you may have experienced when interacting with Greek e-shops, startups and other organisations that make use of PHP and just stick to the nominative case when addressing their partners, customers, users etc.

## Requirements

You need Aspell with the Greek dictionary already installed in your system. Consult the Aspell documentation for more information about how to do this, depending on your Operating System. 

You also need PHP 7.1+ to use this class. You also need the `intl` and `pspell` extensions (this is because the class makes use of the `Normalizer` to quickly replace accented characters with the non-accented/decomposed versions thereof and Pspell provides an interface to Aspell) Other than that, you should be able to make use of it as is.
