<?php namespace Deefour\Authorizer;

use Deefour\Authorizer\Contracts\Authorizee as AuthorizeeContract;

/**
 * Base policy class all application policies are encouraged to extend. Aide
 * expects to pass a user as the first argument to a new policy and the record
 * to authorize against as the second argument.
 */
abstract class Policy {

  /**
   * The user to be authorized
   *
   * @var mixed
   */
  protected $user;

  /**
   * The record/object to authorize against
   *
   * @var mixed
   */
  protected $record;


  /**
   * Sets expectations for dependencies on the policy class and stores references
   * to them locally.
   *
   * @param  mixed  $user
   * @param  mixed  $record
   */
  public function __construct(AuthorizeeContract $user = null, $record) {
    $this->user   = $user;
    $this->record = $record;
  }

  /**
   * Convenience method to call a policy action by passing the action name as a
   * string. This is particularly handy within the context of a view, making the
   * authorization check a bit more human-readable.
   *
   * @if (policy($article)->can('edit'))
   *   // ...
   * @endif
   *
   * @return boolean
   */
  public function can($action) {
    if ( ! method_exists($this, $action)) {
      throw new \BadMethodCallException(sprintf('There is no `%s` method defined on `%s`', $action, static::class));
    }

    $args = func_get_args();

    // pop the action off the stack
    array_shift($args);

    return call_user_func_array([ $this, $action ], $args);
  }

  /**
   * Protects mass-assignment of unauthorized/unwanted attributes
   *
   * @return array
   */
  public function permittedAttributes() {
    throw new \BadMethodCallException('A `permittedAttributes` method has not been defined for this class');
  }

}
