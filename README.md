JrMnt
=====

...is a microframework for PHP unit testing. I wrote it back in 2009 and never did much with it, but it was maybe the first code I ever wrote where I liked the shape and feel of the code. I still like the code in the initial commit, which packed everything into one small PHP file.

Original repo description: "A refreshing and small PHP test framework. With test frameworks, you get into the question of infinite regress: how does one prove the test code is correct? My answer, in this case: keep all the methods small and obvious. Nothing over 25 lines."

Issues from the original repo
=============================

...some of which are actually interesting and, now that I'm copying them over, kinda make me want to mess around with this code again :-)

1. move hasFailingTests into Result object.
    Result can just be an ArrayObject, anything to encapsulate the results.

    this way we can check for failures by doing $result->hasFailingTests instead of the strange $this->hasFailingTests($result).

2. test runner ideas
    so a testRunner could just

    * ask each testfile to find tests
    * ask each testfile to run its tests (don't even need to ask it about finding)
    * aggregate each testfile's results into a huge output object.

    what might it need to do?

    * look through current/given directory/array of directories, at whatever level of recursive depth for testfiles
    * locate testfiles by either
        * tokenizing and comparing class name OR
        * some naming or directory convention (meh) OR
        * some SplFileInfo or ReflectionClass extending object that loops over dirs, instantiates a class, checks if it inherits from JrMnt, then kills the object

3. features for an autoloader
    * should not clobber existing spl autoload functions
        * meaning those listed by calling spl_autoload_functions()
    * should not ignore autoload magic methods in a given class

    basic idea: same as appending to the front of $PATH:

    try my autoload function; then try (everything else in the list)

    * --> maybe this exists already in ezComponents or similar?
        * --> eh, turns out ezComponents Base component does have an autoloader, but userland code has to follow naming conventions. Then you have 'all your (ezComponent) Base' extra code floating around, bloating it up.

4. the use of exceptions for flow control seems inelegant
    problem: PHP has try/catch, not try/catch/finally; unexpected errors may prevent 'teardown' code from executing.

    a better solution: do not set up tests with the assumption that previous tests cleaned up. zero out and re-initialize program state needed to run a test. do not depend on teardown. Which leads to the question: is teardown even useful in PHP testing?

5. check for zero tests. currently getting a foreach error.
    if a test class has zero test cases in it, JrMnt reports a pass, but also throws a foreach error.

6. update for 5.3
    5.3 features I'm aware of atm:

    - namespaces - more SPL built-ins (I forget--is the IteratorIterator interface used to traverse the tests?) - I think late static bindings might make certain parts more testable or cleaner


