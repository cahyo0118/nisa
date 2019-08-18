package com.orraa.demo.repository;

@if($relation->relation_type == 'belongstomany')
import com.orraa.demo.model.{!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!};
import com.orraa.demo.model.{!! (ucfirst(camel_case(str_singular($relation->local_table->name)))) !!};
import com.orraa.demo.model.{!! (ucfirst(camel_case(str_singular($relation->table->name)))) !!};
@elseif($relation->relation_type == 'hasmany')
import com.orraa.demo.model.{!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!};
@endif
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import org.springframework.data.jpa.repository.Modifying;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@if($relation->relation_type == 'belongstomany')
@Repository
public interface {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!}Repository extends CrudRepository<{!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!}, Long> {

    @Query("SELECT ur FROM {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!} ur WHERE ur.{!! camel_case(str_singular($relation->local_table->name)) !!}.{!! camel_case(str_singular($relation->local_key_field->name)) !!} = :{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}")
    List<{!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!}> findBy{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}(@Param("{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}") Long {!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!});

    @Query("SELECT ur.{!! camel_case(str_singular($relation->table->name)) !!} FROM {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!} ur WHERE ur.{!! camel_case(str_singular($relation->local_table->name)) !!}.{!! camel_case(str_singular($relation->local_key_field->name)) !!} = :{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}")
    List<{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> find{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}By{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}(@Param("{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}") Long {!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!});

    @Query("SELECT ur FROM {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!} ur WHERE ur.{!! camel_case(str_singular($relation->table->name)) !!}.{!! camel_case(str_singular($relation->foreign_key_field->name)) !!} = :{!! camel_case(str_singular($relation->table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!}")
    List<{!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!}> findBy{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!}(@Param("{!! camel_case(str_singular($relation->table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!}") Long {!! camel_case(str_singular($relation->table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!});

    @Query("SELECT ur FROM {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!} ur WHERE ur.{!! camel_case(str_singular($relation->local_table->name)) !!}.{!! camel_case(str_singular($relation->local_key_field->name)) !!} = :{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!} AND ur.{!! camel_case(str_singular($relation->table->name)) !!}.{!! camel_case(str_singular($relation->foreign_key_field->name)) !!} = :{!! camel_case(str_singular($relation->table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!}")
    {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!} findBy{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}And{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!}(@Param("{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}") Long {!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}, @Param("{!! camel_case(str_singular($relation->table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!}") Long {!! camel_case(str_singular($relation->table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->foreign_key_field->name))) !!});

    @Transactional
    @Modifying
    @Query(value = "DELETE FROM {!! ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name))) !!} ur WHERE ur.{!! camel_case(str_singular($relation->local_table->name)) !!}.{!! camel_case(str_singular($relation->local_key_field->name)) !!} = :{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}")
    void deleteBy{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}(@Param("{!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!}") Long {!! camel_case(str_singular($relation->local_table->name)) !!}{!! ucfirst(camel_case(str_singular($relation->local_key_field->name))) !!});
}
@elseif($relation->relation_type == 'hasmany')
@Repository
public interface {!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!}Repository extends CrudRepository<{!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!}, Long> {

    @Query("SELECT ur FROM {!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!} ur WHERE ur.user.id = :userId")
    List<{!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!}> findByUserId(@Param("userId") Long userId);

    @Query("SELECT ur FROM {!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!} ur WHERE ur.role.id = :roleId")
    List<{!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!}> findByRoleId(@Param("roleId") Long roleId);

    @Query("SELECT ur FROM {!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!} ur WHERE ur.user.id = :userId AND ur.role.id = :roleId")
    {!! (ucfirst(camel_case(str_singular($relation->local_table->name))) . ucfirst(camel_case(str_singular($relation->table->name)))) !!} findByUserIdAndRoleId(@Param("userId") Long userId, @Param("roleId") Long roleId);

}
@endif
