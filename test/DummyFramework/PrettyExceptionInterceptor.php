<?php
class PrettyExceptionInterceptor extends Berthe_AbstractInterceptor {
    protected function intercept($method, $args) {
        try {
            return $this->invoke($method, $args);
        }
        catch (Berthe_ErrorHandler_Errors $e) {
            echo "A Berthe Logic Error Stack occured : \n";
            $errors = $e->getErrors();
            foreach($errors as $error) {
                echo " - " . $error->getMessage() . '(' . $error->getCode() . ') with data=[' . implode(',', $error->getData()) . "]\n";
            }
        }
        catch(LogicException $e) {
            echo "A logic Exception occured : " . $e->getMessage() . "\n";
        }
        catch(RuntimeException $e) {
            echo "A Runtime Exception occured : " . $e->getMessage() . "\n";
        }
        catch(Exception $e) {
            echo "An unknown Exception occured (which is strange because an interceptor is supposed to catch them all) : " . $e->getMessage() . "\n";
        }
    }
}
